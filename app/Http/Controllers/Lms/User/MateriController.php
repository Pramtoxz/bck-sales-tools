<?php

namespace App\Http\Controllers\Lms\User;

use App\Http\Controllers\Controller;
use App\Models\FeedbackTraining;
use App\Models\Materi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class MateriController extends Controller
{
    public function index($kd_training,$kd_event_training)
    {
        return view('pages.lms.training.materi', ['kd_training' => $kd_training,'kd_event_training' => $kd_event_training]);
    }

    public function all(Request $r)
    {
        // return $r->all();
        $kodeTraining = $r->kd_training;
        $kodeEventTraining = $r->kd_event_training;

        $user = auth()->user()->kd_karyawan;

        $selectMateri = Materi::join('lms.training', 'lms.materi.kd_training', '=', 'lms.training.kd_training')
            ->leftjoin('lms.event_training', 'training.kd_training', '=', 'lms.event_training.kd_training')
            ->leftjoin('lms.peserta_training', 'event_training.kd_event_training', '=', 'lms.peserta_training.kd_event_training')
            ->leftjoin('lms.peserta_test',function($q) use($user){
                $q->on('peserta_test.kd_event_training','event_training.kd_event_training')->where('peserta_test.kd_karyawan',$user);
            })
            ->with([
                'historyActivityTraining'=> function ($query) use ($user,$kodeEventTraining) {
                    $query->where('history_activity_training.kd_karyawan', $user)
                    ->where('history_activity_training.kd_event_training',$kodeEventTraining)->select("id_materi");
                },
                'feedbackTraining' => function ($query) use ($user) {
                $query->where('feedback_training.kd_karyawan', $user);
            }])
            ->where('lms.training.kd_training', $kodeTraining)
            ->where('lms.event_training.kd_event_training', $kodeEventTraining)
            ->where('lms.peserta_training.kd_karyawan', $user)
            ->select('materi.id','materi.filename', 'materi.tipe_materi', 'training.kd_training', 'training.document_pre_test', 'training.document_post_test', 'materi.link', 'peserta_training.id as id_peserta_training', 'peserta_training.kd_karyawan', 'peserta_training.status','event_training.kd_event_training','peserta_test.user_selesai_pre_test','peserta_test.user_selesai_post_test')
            ->get();

        foreach ($selectMateri as $materifile) {
            if ($materifile->tipe_materi === 'document') {
                $materifile->filename = Storage::url('/materi/' . $materifile->filename);
            }
        }
        // return $selectMateri;
        return response()->json($selectMateri);
    }
    public function allNext(Request $r)
    {
        $kodeTraining = $r->kd_training;
        $user = auth()->user()->kd_karyawan;

        // $selectMateri = Materi::with('training')->where('kd_training',$kodeTraining)->get();

        $selectMateri = Materi::join('lms.training', 'lms.materi.kd_training', '=', 'lms.training.kd_training')
            ->leftjoin('lms.event_training', 'training.kd_training', '=', 'lms.event_training.kd_training')
            ->leftjoin('lms.peserta_training', 'event_training.kd_event_training', '=', 'lms.peserta_training.kd_event_training')
            ->where('lms.training.kd_training', $kodeTraining)
            ->where('lms.peserta_training.kd_karyawan', $user)
            ->select('peserta_training.jawaban_pre_test')
            ->get();

        // return $selectMateri;
        return response()->json($selectMateri);
    }
    // public function checkUlasan(Request $r)
    // {
    //     $trainingKode=$r->kd_training;
    //     $user = auth()->user()->kd_karyawan;

    // }
    public function ulasan(Request $r)
    {
        $user = auth()->user()->kd_karyawan;
        try {
            $rules = [
                'rating' =>'required',
                'ulasan' => 'required',
            ];
            $message = [
                "rating.required"=>"rating wajib diisi",
                "ulasan.required"=>"Ulasan Wajib Diisi",    
            ];
            $validator = Validator::make($r->all(), $rules,$message);
            if ($validator->fails()) {
                return response()->json($validator->errors()->first(),422);
            }

            $save = FeedbackTraining::insert([
                "kd_training" => $r->kd_training,
                "kd_karyawan" => $user,
                "rating" => $r->rating,
                "catatan" => $r->ulasan,
                "created_at" => date('Y-m-d H:i:s'),
                "updated_at" => date('Y-m-d H:i:s')
            ]);

            if ($save) {
                return response()->json([
                    "code" => 200,
                    "status" => "true",
                    "message" => "Sukses",
                ]);
            } else {
                return response()->json([
                    "code" => 400,
                    "status" => "false",
                    "message" => "Failed",
                ]);
            }
        } catch (\Exception $th) {
            return response()->json([$th->getMessage()], 500);
        }
    }
}
