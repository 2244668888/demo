@extends('layouts.app')
@section('title')
    SUPPLIER RANKING EDIT
@endsection
@section('content')
    <div class="card">
        <form method="post" action="{{ route('supplier_ranking.update', $Supplierranking->id) }}"
            enctype="multipart/form-data">
            @csrf
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
                                value="{{ date('d-m-Y',strtotime($Supplierranking->date)) }}">
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
                            <select name="supplier_id" id="supplier_id" class="form-select">
                                <option value="" selected disabled>Please Select</option>
                                @foreach ($Suppliers as $Supplier)
                                    <option value="{{ $Supplier->id }}" @selected($Supplierranking->supplier_id == $Supplier->id)>
                                        {{ $Supplier->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-lg-3 col-sm-4 col-12">
                        <div class="mb-3">
                            <label for="ranking" class="form-label">Ranking</label>
                            <select name="ranking" id="ranking" class="form-select">
                                <option value="" selected disabled>Please Select</option>
                                <option value="A" @selected($Supplierranking->ranking == 'A')>A</option>
                                <option value="B" @selected($Supplierranking->ranking == 'B')>B</option>
                                <option value="C" @selected($Supplierranking->ranking == 'C')>C</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-lg-3 col-sm-4 col-12">
                        <div class="mb-3">
                            <label for="ranking_date" class="form-label">Ranking Date</label>
                            <input type="month" name="ranking_date" value="{{ $Supplierranking->ranking_date }}"
                                id="ranking_date" class="form-control">
                        </div>
                    </div>
                </div>
            </div>
            <div class="card-footer">
                <div class="row">
                    <div class="d-flex gap-2 justify-content-between col-12">
                        <a type="button" class="btn btn-info" href="{{ route('supplier_ranking.index') }}">
                            <i class="bi bi-arrow-left"></i> Back
                        </a>
                        <button type="submit" class="btn btn-primary">Update</button>
                    </div>
                </div>
            </div>
        </form>
    </div>
@endsection
