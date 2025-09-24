<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class MusicExpertController extends Controller
{
    private $rules = [
        ["conditions" => ["energetic","like_beats","want_dance"], "cond_type"=>"AND","rule_cf"=>0.9,"conclusion"=>"EDM"],
        ["conditions" => ["happy","like_vocals"], "cond_type"=>"AND","rule_cf"=>0.85,"conclusion"=>"Pop"],
        ["conditions" => ["calm","want_relax"], "cond_type"=>"AND","rule_cf"=>0.95,"conclusion"=>"Lo-fi / Ambient"],
        ["conditions" => ["romantic","want_slow"], "cond_type"=>"AND","rule_cf"=>0.9,"conclusion"=>"R&B / Ballad"],
        ["conditions" => ["angry","energetic"], "cond_type"=>"AND","rule_cf"=>0.9,"conclusion"=>"Rock / Metal"],
        ["conditions" => ["angry"], "cond_type"=>"OR","rule_cf"=>0.6,"conclusion"=>"Rock / Metal"],
        ["conditions" => ["nostalgic"], "cond_type"=>"OR","rule_cf"=>0.8,"conclusion"=>"Indie / Acoustic"],
        ["conditions" => ["calm","happy"], "cond_type"=>"AND","rule_cf"=>0.7,"conclusion"=>"Indie Pop"],
        ["conditions" => ["energetic","like_vocals"], "cond_type"=>"AND","rule_cf"=>0.75,"conclusion"=>"K-Pop / Pop"],
    ];

    private function combineCF($cf1, $cf2) {
        if ($cf1 >= 0 && $cf2 >= 0) {
            return $cf1 + $cf2 * (1 - $cf1);
        }
        if ($cf1 <= 0 && $cf2 <= 0) {
            return $cf1 + $cf2 * (1 + $cf1);
        }
        return ($cf1 + $cf2) / (1 - min(abs($cf1), abs($cf2)));
    }

    private function evaluateRule($rule, $answers) {
        $vals = [];
        foreach ($rule["conditions"] as $cond) {
            $vals[] = $answers[$cond] ?? 0.0;
        }
        if (empty($vals)) return 0.0;

        $symptomCF = ($rule["cond_type"] == "AND") ? min($vals) : max($vals);
        return $symptomCF * ($rule["rule_cf"] ?? 1.0);
    }

    private function forwardChain($rules, $answers) {
        $conclusions = [];
        foreach ($rules as $rule) {
            $cf = $this->evaluateRule($rule, $answers);
            $target = $rule["conclusion"];
            if (isset($conclusions[$target])) {
                $conclusions[$target] = $this->combineCF($conclusions[$target], $cf);
            } else {
                $conclusions[$target] = $cf;
            }
        }
        return $conclusions;
    }

    public function index() {
        $questions = [
            "energetic" => "Seberapa besar kamu merasa ENERGETIC/bersemangat?",
            "like_beats" => "Apakah kamu suka musik dengan beat kuat?",
            "want_dance" => "Apakah ingin musik untuk menari/dance?",
            "happy" => "Seberapa HAPPY/ceria mood kamu?",
            "like_vocals" => "Apakah kamu suka musik dengan vokal dominan?",
            "calm" => "Seberapa CALM/tenang mood kamu?",
            "want_relax" => "Apakah ingin musik untuk relaksasi?",
            "romantic" => "Apakah kamu sedang mood ROMANTIC?",
            "want_slow" => "Apakah ingin musik yang lambat?",
            "angry" => "Seberapa ANGRY/Marah mood kamu?",
            "nostalgic" => "Apakah kamu sedang merasa NOSTALGIC/nostalgia?",
        ];
        return view('music-expert', compact('questions'));
    }

    public function recommend(Request $request) {
        $answers = $request->except('_token');
        $conclusions = $this->forwardChain($this->rules, $answers);

        arsort($conclusions);
        return view('result', compact('conclusions'));
    }
}
