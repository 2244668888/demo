<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Machine;
use App\Models\MachineApi;
use Illuminate\Http\Request;
use App\Models\MachineCount;
use App\Models\ProductionApi;
use App\Models\CallForAssistance;
use App\Models\MachineDownime;
use App\Models\MachinePreperation;
use App\Models\ProductionOutputTraceabilityDetail;

class APIController extends Controller
{
    //CALL FOR ASSISTANCE
    public function call_for_assistance(Request $request)
    {
        try {
            $responsess = json_decode($request->getContent());
            $responses = $responsess->data;
            $response = json_encode($responses->data);
            $response = json_decode($response, true);
            $datetime = Carbon::parse($response['datetime'], 'Asia/Kuala_Lumpur')->format('d-m-Y h:i:s A') ?? null;
            $mc_no = $response['mc_no'] ?? null;
            $call = $response['call'] ?? null;
            $package_no = $response['package_no'] ?? null;
            $msg_no = $response['msg_no'] ?? null;

            if (!$mc_no) {
                return response()->json(["status" => '500', "msg" => 'mc_no cannot be null!', "response" => $responsess]);
            }

            $machine_exist = Machine::where('code', '=', $mc_no)->first();
            if (!$machine_exist) {
                return response()->json(["status" => '500', "msg" => 'Machine Not Found!', "response" => $responsess]);
            }

            if ($call == 1) {
                // $machine_api = MachineApi::where('mc_no', '=', $mc_no)->whereNull('end_time')->orderBy('id', 'DESC')->first();
                // if(!$machine_api){
                //     return response()->json(["status"=>'500',"msg"=>'Machine: '.$mc_no.' Currently Not Running!', "response"=>$responsess]);
                // }
                $production = ProductionOutputTraceabilityDetail::where('machine_id', '=', $machine_exist->id)->whereNull('end_time')->orderBy('id', 'DESC')->first();
                if (!$production) {
                    return response()->json(["status" => '500', "msg" => 'Machine: ' . $mc_no . ' Currently Not Running In Production!', "response" => $responsess]);
                }
                $check_already = CallForAssistance::where('mc_no', '=', $mc_no)->where('call', '=', 1)->first();
                if ($check_already) {
                    return response()->json(["status" => '500', "msg" => 'Already Call for Assistance against Machine: ' . $mc_no . '', "response" => $responsess]);
                }
                CallForAssistance::create([
                    'datetime' => $datetime,
                    'mc_no' => $mc_no,
                    'call' => $call,
                    'package_no' => $package_no,
                    'msg_no' => $msg_no,
                    'status' => 'Not-initiated'
                ]);
                return response()->json(["status" => '200', "msg" => 'Call for Assistance Created of Machine: ' . $mc_no . '', "response" => $responsess]);
            } else if ($call == 0) {
                $package_ca = CallForAssistance::where('mc_no', '=', $mc_no)->where('call', '=', 1)->first();
                if ($package_ca) {
                    $package_ca->update([
                        'attended_datetime' => $datetime,
                        'call' => $call,
                        'status' => 'Completed'
                    ]);
                    return response()->json(["status" => '200', "msg" => 'Call for Assistance Completed of Machine: ' . $mc_no . '', "response" => $responsess]);
                } else {
                    return response()->json(["status" => '500', "msg" => 'Call for Assistance not found against ' . $mc_no . '!', "response" => $responsess]);
                }
            } else {
                return response()->json(["status" => '500', "msg" => 'Call for Assistance call should be 1 or 0!', "response" => $responsess]);
            }
        } catch (\Exception $th) {
            return $th->getMessage();
        }
    }

    //MACHINE UPTIME
    public function machine_up_time(Request $request)
    {
        try {
            $responsess = json_decode($request->getContent());
            $responses = $responsess->data;
            $response = json_encode($responses->data);
            $response = json_decode($response, true);

            $datetime = Carbon::parse($response['datetime'], 'Asia/Kuala_Lumpur')->format('d-m-Y h:i:s A') ?? null;
            $mc_no = $response['mc_no'] ?? null;
            $uptimeStatus = $response['uptimeStatus'] ?? null;

            if (!$mc_no) {
                return response()->json(["status" => '500', "msg" => 'mc_no cannot be null!', "response" => $responsess]);
            }

            $machine_exist = Machine::where('code', '=', $mc_no)->first();
            if (!$machine_exist) {
                return response()->json(["status" => '500', "msg" => 'Machine Not Found!', "response" => $responsess]);
            }

            if ($uptimeStatus == 1) {
                $running = MachineApi::where('mc_no', '=', $mc_no)->whereNull('end_time')->orderBy('id', 'DESC')->first();

                if ($running) {
                    return response()->json(["status" => '500', "msg" => 'Machine: ' . $mc_no . ' Already Started!', "response" => $responsess]);
                }

                MachineApi::create([
                    'start_time' => $datetime,
                    'end_time' => null,
                    'mc_no' => $mc_no
                ]);

                $production = ProductionOutputTraceabilityDetail::where('machine_id', '=', $machine_exist->id)->whereNull('end_time')->orderBy('id', 'DESC')->first();

                $productions = ProductionApi::where('mc_no', '=', $mc_no)->whereNull('end_time')->orderBy('id', 'DESC')->first();
                $preperation = MachinePreperation::where('mc_no', '=', $mc_no)->whereNull('end_time')->orderBy('id', 'DESC')->first();
                if (!$productions && !$preperation) {
                    MachineDownime::create([
                        'production_id' => $production->pot_id,
                        'start_time' => $datetime,
                        'end_time' => null,
                        'mc_no' => $mc_no
                    ]);
                } else {
                    $downtime = MachineDownime::where('mc_no', '=', $mc_no)->whereNull('end_time')->orderBy('id', 'DESC')->first();
                    if ($downtime) {
                        $downtime->update([
                            'end_time' => $datetime,
                        ]);
                    }
                }

                return response()->json(["status" => '200', "msg" => 'Machine: ' . $mc_no . ' Started Successfully!', "response" => $responsess]);
            } else if ($uptimeStatus == 0) {
                $running = MachineApi::where('mc_no', '=', $mc_no)->whereNull('end_time')->orderBy('id', 'DESC')->first();

                if ($running) {
                    $running->update([
                        'end_time' => $datetime,
                    ]);

                    $downtime = MachineDownime::where('mc_no', '=', $mc_no)->whereNull('end_time')->orderBy('id', 'DESC')->first();
                    if ($downtime) {
                        $downtime->update([
                            'end_time' => $datetime,
                        ]);
                    }
                    return response()->json(["status" => '200', "msg" => 'Machine: ' . $mc_no . ' Stopped Successfully!', "response" => $responsess]);
                } else {
                    return response()->json(["status" => '500', "msg" => 'Machine: ' . $mc_no . ' Currently Not Running!', "response" => $responsess]);
                }
            } else {
                return response()->json(["status" => '500', "msg" => 'Invalid Uptime Status!', "response" => $responsess]);
            }
        } catch (\Exception $th) {
            return response()->json(["status" => '500', "msg" => $th->getMessage(), "response" => $responsess]);
        }
    }

    //PRODUCTION
    public function production(Request $request)
    {
        try {
            $responsess = json_decode($request->getContent());
            $responses = $responsess->data;
            $response = json_encode($responses->data);
            $response = json_decode($response, true);

            $datetime = Carbon::parse($response['datetime'], 'Asia/Kuala_Lumpur')->format('d-m-Y h:i:s A') ?? null;
            $mc_no = $response['mc_no'] ?? null;
            $prodStatus = $response['prodStatus'] ?? null;

            if (!$mc_no) {
                return response()->json(["status" => '500', "msg" => 'mc_no cannot be null!', "response" => $responsess]);
            }

            $machine_exist = Machine::where('code', '=', $mc_no)->first();
            if (!$machine_exist) {
                return response()->json(["status" => '500', "msg" => 'Machine Not Found!', "response" => $responsess]);
            }

            $running = MachineApi::where('mc_no', '=', $mc_no)->whereNull('end_time')->orderBy('id', 'DESC')->first();
            if (!$running) {
                return response()->json(["status" => '500', "msg" => 'Can`t Start Production, Machine: ' . $mc_no . ' Not Running!', "response" => $responsess]);
            }

            $production = ProductionOutputTraceabilityDetail::where('machine_id', '=', $machine_exist->id)->whereNull('end_time')->orderBy('id', 'DESC')->first();
            if (!$production) {
                return response()->json(["status" => '500', "msg" => 'Machine: ' . $mc_no . ' Currently Not Running In Production (System)!', "response" => $responsess]);
            }

            if ($prodStatus == 1) {
                $running = ProductionApi::where('mc_no', '=', $mc_no)->whereNull('end_time')->orderBy('id', 'DESC')->first();

                if ($running) {
                    return response()->json(["status" => '500', "msg" => 'Mahine : ' . $mc_no . ' Already In Production!', "response" => $responsess]);
                }

                ProductionApi::create([
                    'production_id' => $production->pot_id,
                    'start_time' => $datetime,
                    'end_time' => null,
                    'mc_no' => $mc_no
                ]);

                $preperations = MachinePreperation::where('mc_no', '=', $mc_no)->whereNull('end_time')->orderBy('id', 'DESC')->first();
                if ($preperations) {
                    $preperations->update([
                        'end_time' => $datetime,
                    ]);
                }

                $productions = ProductionApi::where('mc_no', '=', $mc_no)->whereNull('end_time')->orderBy('id', 'DESC')->first();
                $preperation = MachinePreperation::where('mc_no', '=', $mc_no)->whereNull('end_time')->orderBy('id', 'DESC')->first();
                if (!$productions && !$preperation) {
                    MachineDownime::create([
                        'production_id' => $production->pot_id,
                        'start_time' => $datetime,
                        'end_time' => null,
                        'mc_no' => $mc_no
                    ]);
                } else {
                    $downtime = MachineDownime::where('mc_no', '=', $mc_no)->whereNull('end_time')->orderBy('id', 'DESC')->first();
                    if ($downtime) {
                        $downtime->update([
                            'end_time' => $datetime,
                        ]);
                    }
                }

                return response()->json(["status" => '200', "msg" => 'Production on Machine: ' . $mc_no . ' Started Successfully!', "response" => $responsess]);
            } else if ($prodStatus == 0) {
                $running = ProductionApi::where('mc_no', '=', $mc_no)->whereNull('end_time')->orderBy('id', 'DESC')->first();

                if ($running) {
                    $running->update([
                        'end_time' => $datetime,
                    ]);

                    $productions = ProductionApi::where('mc_no', '=', $mc_no)->whereNull('end_time')->orderBy('id', 'DESC')->first();
                    $preperation = MachinePreperation::where('mc_no', '=', $mc_no)->whereNull('end_time')->orderBy('id', 'DESC')->first();
                    if (!$productions && !$preperation) {
                        MachineDownime::create([
                            'production_id' => $production->pot_id,
                            'start_time' => $datetime,
                            'end_time' => null,
                            'mc_no' => $mc_no
                        ]);
                    } else {
                        $downtime = MachineDownime::where('mc_no', '=', $mc_no)->whereNull('end_time')->orderBy('id', 'DESC')->first();
                        if ($downtime) {
                            $downtime->update([
                                'end_time' => $datetime,
                            ]);
                        }
                    }

                    return response()->json(["status" => '200', "msg" => 'Production on Machine: ' . $mc_no . ' Stopped Successfully!', "response" => $responsess]);
                } else {
                    return response()->json(["status" => '500', "msg" => 'Machine: ' . $mc_no . ' Currently Not In Production!', "response" => $responsess]);
                }
            } else {
                return response()->json(["status" => '500', "msg" => 'Invalid Production Status!', "response" => $responsess]);
            }
        } catch (\Exception $th) {
            return response()->json(["status" => '500', "msg" => $th->getMessage(), "response" => $responsess]);
        }
    }

    //MACHINE PREPERATION
    public function machine_preperation(Request $request)
    {
        try {
            $responsess = json_decode($request->getContent());
            $responses = $responsess->data;
            $response = json_encode($responses->data);
            $response = json_decode($response, true);

            $datetime = Carbon::parse($response['datetime'], 'Asia/Kuala_Lumpur')->format('d-m-Y h:i:s A') ?? null;
            $mc_no = $response['mc_no'] ?? null;
            $mcPrep = $response['mcPrep'] ?? null;

            if (!$mc_no) {
                return response()->json(["status" => '500', "msg" => 'mc_no cannot be null!', "response" => $responsess]);
            }

            $machine_exist = Machine::where('code', '=', $mc_no)->first();
            if (!$machine_exist) {
                return response()->json(["status" => '500', "msg" => 'Machine Not Found!', "response" => $responsess]);
            }

            $running = MachineApi::where('mc_no', '=', $mc_no)->whereNull('end_time')->orderBy('id', 'DESC')->first();
            if (!$running) {
                return response()->json(["status" => '500', "msg" => 'Can`t Start Production, Machine: ' . $mc_no . ' Not Running!', "response" => $responsess]);
            }

            $production = ProductionOutputTraceabilityDetail::where('machine_id', '=', $machine_exist->id)->whereNull('end_time')->orderBy('id', 'DESC')->first();
            if (!$production) {
                return response()->json(["status" => '500', "msg" => 'Machine: ' . $mc_no . ' Currently Not Running In Production (System)!', "response" => $responsess]);
            }

            if ($mcPrep == 1) {
                $running = MachinePreperation::where('mc_no', '=', $mc_no)->whereNull('end_time')->orderBy('id', 'DESC')->first();

                if ($running) {
                    return response()->json(["status" => '500', "msg" => 'Mahine : ' . $mc_no . ' Already In Preperation!', "response" => $responsess]);
                }

                MachinePreperation::create([
                    'production_id' => $production->pot_id,
                    'start_time' => $datetime,
                    'end_time' => null,
                    'mc_no' => $mc_no
                ]);

                $production->update([
                    'end_time' => $datetime,
                ]);

                $productions = ProductionApi::where('mc_no', '=', $mc_no)->whereNull('end_time')->orderBy('id', 'DESC')->first();
                $preperation = MachinePreperation::where('mc_no', '=', $mc_no)->whereNull('end_time')->orderBy('id', 'DESC')->first();
                if (!$productions && !$preperation) {
                    MachineDownime::create([
                        'production_id' => $production->pot_id,
                        'start_time' => $datetime,
                        'end_time' => null,
                        'mc_no' => $mc_no
                    ]);
                } else {
                    $downtime = MachineDownime::where('mc_no', '=', $mc_no)->whereNull('end_time')->orderBy('id', 'DESC')->first();
                    if ($downtime) {
                        $downtime->update([
                            'end_time' => $datetime,
                        ]);
                    }
                }

                return response()->json(["status" => '200', "msg" => 'Machine: ' . $mc_no . ' Successfully Set on Preperation!', "response" => $responsess]);
            } else if ($mcPrep == 0) {
                $running = MachinePreperation::where('mc_no', '=', $mc_no)->whereNull('end_time')->orderBy('id', 'DESC')->first();

                if ($running) {
                    $running->update([
                        'end_time' => $datetime,
                    ]);

                    ProductionApi::create([
                        'production_id' => $production->pot_id,
                        'start_time' => $datetime,
                        'end_time' => null,
                        'mc_no' => $mc_no
                    ]);

                    $productions = ProductionApi::where('mc_no', '=', $mc_no)->whereNull('end_time')->orderBy('id', 'DESC')->first();
                    $preperation = MachinePreperation::where('mc_no', '=', $mc_no)->whereNull('end_time')->orderBy('id', 'DESC')->first();
                    if (!$productions && !$preperation) {
                        MachineDownime::create([
                            'production_id' => $production->pot_id,
                            'start_time' => $datetime,
                            'end_time' => null,
                            'mc_no' => $mc_no
                        ]);
                    } else {
                        $downtime = MachineDownime::where('mc_no', '=', $mc_no)->whereNull('end_time')->orderBy('id', 'DESC')->first();
                        if ($downtime) {
                            $downtime->update([
                                'end_time' => $datetime,
                            ]);
                        }
                    }

                    return response()->json(["status" => '200', "msg" => 'Machine: ' . $mc_no . ' Successfully UnSet on Preperation!', "response" => $responsess]);
                } else {
                    return response()->json(["status" => '500', "msg" => 'Machine: ' . $mc_no . ' Currently Not In Production!', "response" => $responsess]);
                }
            } else {
                return response()->json(["status" => '500', "msg" => 'Invalid Production Status!', "response" => $responsess]);
            }
        } catch (\Exception $th) {
            return response()->json(["status" => '500', "msg" => $th->getMessage(), "response" => $responsess]);
        }
    }

    //MACHINE COUNT
    public function machine_count(Request $request)
    {
        try {
            $responsess = json_decode($request->getContent());
            $responses = $responsess->data;
            $response = json_encode($responses->data);
            $response = json_decode($response, true);
            $datetime = Carbon::parse($response['datetime'], 'Asia/Kuala_Lumpur')->format('d-m-Y h:i:s A') ?? null;
            $mc_no = $response['mc_no'] ?? null;
            $count = $response['count'] ?? null;

            if (!$mc_no) {
                return response()->json(["status" => '500', "msg" => 'mc_no cannot be null!', "response" => $responsess]);
            }

            $machine_exist = Machine::where('code', '=', $mc_no)->first();
            if (!$machine_exist) {
                return response()->json(["status" => '500', "msg" => 'Machine Not Found!', "response" => $responsess]);
            }

            $running = MachineApi::where('mc_no', '=', $mc_no)->whereNull('end_time')->orderBy('id', 'DESC')->first();
            if (!$running) {
                return response()->json(["status" => '500', "msg" => 'Machine: ' . $mc_no . ' Not Running!', "response" => $responsess]);
            }

            $production = ProductionOutputTraceabilityDetail::where('machine_id', '=', $machine_exist->id)->whereNull('end_time')->orderBy('id', 'DESC')->first();
            if (!$production) {
                return response()->json(["status" => '500', "msg" => 'Machine: ' . $mc_no . ' Currently Not Running In Production (System)!', "response" => $responsess]);
            }

            $production_api = ProductionApi::where('mc_no', '=', $mc_no)->whereNull('end_time')->orderBy('id', 'DESC')->first();
            $machine_preperation = MachinePreperation::where('mc_no', '=', $mc_no)->whereNull('end_time')->orderBy('id', 'DESC')->first();
            if (!$production_api && !$machine_preperation) {
                return response()->json(["status" => '500', "msg" => 'Machine: ' . $mc_no . ' Not In Production or Not In Preperation!', "response" => $responsess]);
            }

            MachineCount::create([
                'production_id' => $production->pot_id,
                'datetime' => $datetime,
                'mc_no' => $mc_no,
                'count' => $count
            ]);
            return response()->json(["status" => '200', "msg" => 'Count Received on Machine: ' . $mc_no . '', "response" => $responsess]);
        } catch (\Exception $th) {
            return $th->getMessage();
        }
    }
}
