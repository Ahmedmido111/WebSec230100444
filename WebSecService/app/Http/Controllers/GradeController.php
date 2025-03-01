namespace App\Http\Controllers;

use App\Models\Grade;
use App\Models\Term;
use Illuminate\Http\Request;

class GradeController extends Controller
{
    public function index()
    {
        $terms = Term::with('grades')->get();
        $cumulative_total_credit_hours = (string) $terms->sum('total_credit_hours');
        $cumulative_gpa = (string) $terms->avg('gpa');

        return view('grades.index', compact('terms', 'cumulative_total_credit_hours', 'cumulative_gpa'));
    }

    public function create()
    {
        return view('grades.create');
    }

    public function store(Request $request)
    {
        // ...validation and storing logic...
    }

    public function edit(Grade $grade)
    {
        return view('grades.edit', compact('grade'));
    }

    public function update(Request $request, Grade $grade)
    {
        // ...validation and updating logic...
    }

    public function destroy(Grade $grade)
    {
        $grade->delete();
        return redirect()->route('grades.index');
    }
}
