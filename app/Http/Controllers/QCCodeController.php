<?php

namespace App\Http\Controllers;

use App\Models\QCCode;
use Illuminate\Http\Request;
use Illuminate\Support\Env;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;

class QCCodeController extends Controller {

    private $req;

    public function __construct(Request $request) {
        $this->req = $request;

        $this->middleware(function ($request, $next) {
            $access_flag = false;

            if (Session::has('login') && Session::get('login')) {
                $access_flag = true;
            }

            if ($this->req->has('token') && $this->req->get('token') == Env::get('TOKEN')) {
                $access_flag = true;
            }

            if (!$access_flag) {
                response()->json(['code' => 500, 'msg' => 'Access Denied.', 'flag' => $access_flag])->send();
                die();
            }

            return $next($request);
        });
    }

    /**
     * 列出所有的 k
     *
     * GET -> `/codes`
     *
     * @return array
     */
    public function show_k_list(): array {
        $codes = QCCode::select('k')->distinct()->get();

        return ['code' => 0, 'data' => $codes];
    }

    /**
     * 根据 k 列出所有的 p
     *
     * GET -> `/codes/k/{k}`
     *
     * @param $k
     * @return array
     */
    public function show_p_list_of_k($k): array {
        $codes = QCCode::where('k', $k)->select('p')->distinct()->get();
        return ['code' => 0, 'data' => $codes];
    }

    /**
     * 根据 k,p 列出 QC 码
     *
     * GET -> `/codes/k/{k}/p/{p}`
     *
     * @param $k
     * @param $p
     * @return array
     */
    public function show_code_list_by_k_p($k, $p): array {
        $codes = QCCode::where('k', $k)->where('p', $p)->get();
        return ['code' => 0, 'data' => $codes];
    }


    /**
     * 查看 QC 码详情
     *
     * GET -> `/codes/{id}`
     *
     * @param $id
     * @return array
     */
    public function show_detail($id): array {
        $code = QCCode::where('id', $id)->first();
        if (!$code) {
            return ['code' => 404, 'msg' => 'Not Found'];
        }

        return ['code' => 0, 'data' => $code];
    }

    /**
     * 添加 QC 码
     *
     * POST -> `/codes`
     *
     * - k
     * - p
     * - code: 码字表示
     * - n: optional
     *
     * @return array
     */
    public function add_code(): array {
        $k = $this->req->get('k');
        $p = $this->req->get('p');
        $code_str = $this->req->get('code');

        if (!$k || !$p || !$code_str) {
            return ['code' => 500, 'msg' => 'Args Error. [k,p,code] are required.'];
        }

        $code = QCCode::where('k', $k)->where('p', $p)->where('code', $code_str)->first();
        if ($code) {
            return ['code' => 500, 'msg' => 'Already exists', 'data' => $code];
        }

        $code = new QCCode();
        $code->k = $k;
        $code->p = $p;
        $code->code = $code_str;
        $code->n = $this->req->has('n') ? $this->req->get('n') : $k * $p;
        $code->save();

        return ['code' => 0, 'msg' => 'Saved', 'data' => $code];
    }

    /**
     * 更新详情
     *
     * PUT -> `/codes/{id}`
     *
     * - d
     * - generator_matrix
     * - weight_enumerator
     * - dual_n
     * - dual_k
     * - dual_d
     * - dual_generator_matrix
     * - dual_weight_enumerator
     *
     * @param $id
     * @return array
     */
    public function update_detail($id): array {
        /* @var $code QCCode */
        $code = QCCode::where('id', $id)->first();
        if (!$code) {
            return ['code' => 404, 'msg' => 'Not Found'];
        }

        if ($this->req->has('d')) {
            $code->d = $this->req->get('d');
        }

        if ($this->req->has('generator_matrix')) {
            $code->generator_matrix = $this->req->get('generator_matrix');
        }

        if ($this->req->has('weight_enumerator')) {
            $code->weight_enumerator = $this->req->get('weight_enumerator');
        }

        if ($this->req->has('dual_n')) {
            $code->dual_n = $this->req->get('dual_n');
        }

        if ($this->req->has('dual_k')) {
            $code->dual_k = $this->req->get('dual_k');
        }

        if ($this->req->has('dual_d')) {
            $code->dual_d = $this->req->get('dual_d');
        }

        if ($this->req->has('dual_generator_matrix')) {
            $code->dual_generator_matrix = $this->req->get('dual_generator_matrix');
        }

        if ($this->req->has('dual_weight_enumerator')) {
            $code->dual_weight_enumerator = $this->req->get('dual_weight_enumerator');
        }

        if ($code->dual_d) {
            $code->status = QCCode::STATUS_DONE;
        }

        $code->save();

        return ['code' => 0, 'msg' => 'Updated'];
    }

    /**
     * 获取最好的码字列表
     *
     * GET -> `/codes/best-table`
     *
     * @return array
     */
    public function get_best_table(): array {
        $results = QCCode::groupBy(['k', 'p'])->get(['k', 'p', DB::raw('max(d) as max_d')]);
        return ['code' => 0, 'data' => $results];
    }

    /**
     * 获得最好的对偶码列表
     *
     * GET -> `/dual-codes/best-table`
     *
     * @return array
     */
    public function get_dual_best_table(): array {
        $results = QCCode::groupBy(['k', 'p'])->get(['k', 'p', DB::raw('max(dual_d) as max_dual_d')]);
        return ['code' => 0, 'data' => $results];
    }

    /**
     * 获取 pending 列表
     *
     * GET -> `/codes/pending-list`
     *
     * - query_size
     *
     * @return array
     */
    public function get_dual_pending_list(): array {
        $query_size = 10;
        if ($this->req->has('query_size')) {
            $query_size = $this->req->get('query_size');
        }
        if ($query_size > 1000) {
            $query_size = 1000;
        }
        $codes = QCCode::where('status', 0)->limit($query_size)->get();
        foreach ($codes as $code) {
            /* @var $code QCCode */
            $code->status = QCCode::STATUS_PROCESSING;
            $code->save();
        }
        return ['code' => 0, 'data' => $codes];
    }
}
