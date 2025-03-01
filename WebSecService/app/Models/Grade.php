namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Grade extends Model
{
    use HasFactory;

    protected $fillable = ['course', 'grade', 'credit_hours', 'term_id'];

    public function term()
    {
        return $this->belongsTo(Term::class);
    }
}
