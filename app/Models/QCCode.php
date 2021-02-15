<?php

namespace App\Models;

use DateTime;
use Illuminate\Database\Eloquent\Model;

/**
 * Class QCCode
 * @package App\Models
 *
 * @property int id
 * @property int k
 * @property int p
 * @property int n
 * @property int d
 * @property string code
 * @property string generator_matrix
 * @property string weight_enumerator
 * @property int dual_n
 * @property int dual_k
 * @property int dual_d
 * @property string dual_generator_matrix
 * @property string dual_weight_enumerator
 * @property DateTime created_at
 * @property DateTime updated_at
 *
 */
class QCCode extends Model {

}
