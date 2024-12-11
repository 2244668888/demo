@extends('layouts.app')
@section('title')
    SUPPLIER RANKING VIEW
@endsection
@section('content')
    <div class="card">
        <div class="card-body">
            <div class="row">
                <div class="col-lg-3 col-sm-4 col-12">
                    <div class="mb-3">
                        <label for="created_by" class="form-label">Created By</label>
                        <input type="text" readonly name="created_by" id="created_by" class="form-control"
                            value="{{ Auth::user()->user_name }}">
                    </div>
                </div>
                <div class="col-lg-3 col-sm-4 col-12">
                    <div class="mb-3">
                        <label for="date" class="form-label">Created Date</label>
                        <input type="text" readonly name="date" id="date" class="form-control"
                            value="{{ $Supplierranking->date }}">
                    </div>
                </div>
            </div>
            <br>
            <div class="row">
                <h5>SUPPLIER DETAILS</h5>
            </div>
            <br>
            <div class="row">
                <div class="col-lg-3 col-sm-4 col-12">
                    <div class="mb-3">
                        <label for="supplier_id" class="form-label">Supplier Name</label>
                        <input type="text" readonly class="form-control"
                            value="{{ $Supplierranking->supplier->name ?? '' }}">
                    </div>
                </div>
                <div class="col-lg-3 col-sm-4 col-12">
                    <div class="mb-3">
                        <label for="ranking" class="form-label">Ranking</label>
                        <input type="text" readonly class="form-control" value="{{ $Supplierranking->ranking ?? '' }}">
                    </div>
                </div>
                <div class="col-lg-3 col-sm-4 col-12">
                    <div class="mb-3">
                        <label for="ranking_date" class="form-label">Ranking Date</label>
                        <input type="month" readonly name="ranking_date" value="{{ $Supplierranking->ranking_date }}"
                                id="ranking_date" class="form-control">
                    </div>
                </div>
            </div>
        </div>
        <div class="card-footer">
            <div class="row">
                <div class="d-flex gap-2 justify-content-start col-12">
                    <a type="button" class="btn btn-info" href="{{ route('supplier_ranking.index') }}">
                        <i class="bi bi-arrow-left"></i> Back
                    </a>
                </div>
            </div>
        </div>
    </div>
@endsection
