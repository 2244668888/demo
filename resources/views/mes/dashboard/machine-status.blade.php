@extends('layouts.app')
@section('title')
    DASHBOARD / MACHINE STATUS
@endsection
@section('content')
    <style>
        .border-red {
            border: 2px solid red;
        }

        .blink {
            animation: blinker 1s linear infinite;
        }

        .blink-border {
            animation: border-blinker 1s linear infinite;
        }

        @keyframes blinker {
            50% {
                opacity: 0;
            }
        }

        @keyframes border-blinker {
            50% {
                border-color: transparent;
            }
        }
    </style>
    <div class="row" id="machine-status-container">
    </div>
    <script>
        function fetchMachineStatus() {
            $.ajax({
                url: "{{ route('machine_status.generate') }}",
                method: "GET",
                success: function(data) {
                    let container = $('#machine-status-container');
                    container.empty();
                    data.forEach(machine => {
                        let card = `
                            <div class="col-md-4 mb-3">
                                <div class="card ${machine.call_for_assistance ? 'border-red blink-border' : ''}">
                                    <div class="card-header text-center p-0">
                                      <h5 style="
                                                background: ${
                                                    machine.production
                                                        ? machine.production.production.status === "Not-initiated" || machine.production.production.status === "Stop" || machine.production.production.status === "Pause"
                                                            ? "grey"
                                                            : machine.machine_preperation
                                                            ? "yellow"
                                                            : !machine.machine_production && !machine.machine_preperation
                                                            ? "red"
                                                            :"grey"
                                                        : "grey" // Default to grey if production is null
                                                };
                                            ">
                                                (${machine.code}) ${machine.name}
                                            </h5>


                                        <div>Machine Status: <span class="${
                                            machine.machine_status === 'ON' ? 'text-success' : 'text-danger'
                                        }">${machine.machine_status}</span></div>
                                    </div>
                                    <hr>
                                    <div class="card-body">
                                        <div class="row"><div class="col-6"><strong>PRODUCTION ORDER NO:</strong></div><div class="col-6">${machine.production?.production?.po_no ?? 'N/A'}</div></div>
                                        <div class="row"><div class="col-6"><strong>PART NO:</strong></div><div class="col-6">${machine.production?.production?.product?.part_no ?? 'N/A'}</div></div>
                                        <div class="row"><div class="col-6"><strong>PART NAME:</strong></div><div class="col-6">${machine.production?.production?.product?.part_name ?? 'N/A'}</div></div>
                                        <div class="row"><div class="col-6"><strong>PLANNED QTY:</strong></div><div class="col-6">${machine.production?.production?.planned_qty ?? 'N/A'}</div></div>
                                        <div class="row"><div class="col-6"><strong>PRODUCED QTY:</strong></div><div class="col-6">${machine.count ?? 'N/A'}</div></div>
                                        <div class="row"><div class="col-6"><strong>REJECTED QTY:</strong></div><div class="col-6">${machine.production?.production?.total_rejected_qty ?? 'N/A'}</div></div>
                                        <div class="row"><div class="col-6"><strong>STATUS ORDER:</strong></div><div class="col-6">
                                            ${
                                                machine.production
                                                    ? machine.production.production.status === 'Not-initiated'
                                                        ? '<span class="badge bg-secondary">Not-initiated</span>'
                                                        : machine.production.production.status === 'Start'
                                                        ? '<span class="badge bg-success">Started</span>'
                                                        : machine.production.production.status === 'Pause'
                                                        ? '<span class="badge bg-warning">Paused</span>'
                                                        : machine.production.production.status === 'Stop'
                                                        ? '<span class="badge bg-danger">Stopped</span>'
                                                        : '<span class="badge bg-info">Checked</span>'
                                                    : '<span class="badge bg-secondary">Not-initiated</span>'
                                            }
                                        </div></div>
                                        <div class="row"><div class="col-6"><strong>SHIFT:</strong></div><div class="col-6">${
                                            machine.production
                                                ? machine.production.production.shift === 'DAY'
                                                    ? 'Day <i class="bi bi-sun"></i>'
                                                    : 'NIGHT <i class="bi bi-moon"></i>'
                                                : 'N/A'
                                        }</div></div>
                                        <div class="row mt-3">
                                            <div class="col-12 text-center">
                                                ${
                                                    machine.call_for_assistance
                                                        ? '<h5 class="text-danger blink">CALL FOR ASSISTANCE</h5>'
                                                        : ''
                                                }
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        `;
                        container.append(card);
                    });
                },
                error: function(err) {
                    console.error("Error fetching machine status:", err);
                }
            });
        }

        $(document).ready(function() {
            fetchMachineStatus();
            setInterval(fetchMachineStatus, 10000);
        });
    </script>
@endsection
