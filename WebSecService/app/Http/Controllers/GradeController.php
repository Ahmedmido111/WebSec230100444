<?php

namespace App\Http\Controllers;

use App\Models\Grade;
use App\Models\Term;
use Illuminate\Http\Request;

class GradeController extends Controller
{
    public function index()
    {
        $terms = Term::with('grades')->get();
        $cumulative_total_credit_hours = $terms->sum('total_credit_hours');
        $cumulative_gpa = $cumulative_total_credit_hours > 0 ? $terms->sum(function ($term) {
            return $term->gpa * $term->total_credit_hours;
        }) / $cumulative_total_credit_hours : 0;

        return view('grades.index', compact('terms', 'cumulative_total_credit_hours', 'cumulative_gpa'));
    }

    public function create()
    {
        return view('grades.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'course' => 'required',
            'grade' => 'required',
            'credit_hours' => 'required|integer',
        ]);

        Grade::create($request->all());

        return redirect()->route('grades.index');
    }

    public function edit(Grade $grade)
    {
        return view('grades.edit', compact('grade'));
    }

    public function update(Request $request, Grade $grade)
    {
        $request->validate([
            'course' => 'required',
            'grade' => 'required',
            'credit_hours' => 'required|integer',
        ]);

        $grade->update($request->all());

        return redirect()->route('grades.index');
    }

    public function destroy(Grade $grade)
    {
        $grade->delete();

        return redirect()->route('grades.index');
    }
}
