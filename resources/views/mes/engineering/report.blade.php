@extends('layouts.app')
@section('title')
    BOM REPORT
@endsection

@section('content')

<style>
    /* .select2-selection__choice,
.select2-selection__choice__remove {
    pointer-events: none;
}

.select2-selection__choice[data-bs-original-title],
.select2-selection__choice__remove[data-bs-original-title] {
    display: none;
} */
/*
[aria-describedby] {
    pointer-events: none;
} */

.tooltip {
  display: none !important;
}
</style>

    <div class="card">
        <div class="card-body">
            <h6 class="card-title mb-3">Select Product</h6>
            <div class="row">
                <form method="post" action="{{ route('bom.report.genrate') }}" enctype="multipart/form-data">
                    @csrf
                    <div class="col-md-6">
                        <select class="form-select" name="product_id[]" multiple>
                            @foreach ($products as $product)
                                <option value="{{ $product->id }}"
                                    @if (isset($selected_product_ids))
                                        @foreach ($selected_product_ids as $selected_product_id)
                                        {{ $selected_product_id == $product->id ? 'selected' : '' }}
                                        @endforeach
                                    @endif
                                >{{ $product->part_no }} - {{ $product->part_name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-12">
                        <button class="btn btn-primary float-end" type="submit">Generate</button>
                        </form>
                        <form action="{{ route('bom.report.export') }}" method="POST" style="display: inline;">
                            @csrf
                            <input type="hidden" name="id"
                                @isset($selected_product_ids) value="{{ json_encode($selected_product_ids) }}" @else value="0" @endif/>
                            <button class="btn btn-dark float-end me-2" type="submit">Download</button>
                        </form>
                    </div>
            </div>
        </div>
    </div>
    <div class="card mt-5">
        <div class="card-body">
            @if (isset($bom_trees))
                @foreach ($bom_trees as $bomTree)
                    @if (isset($bomTree))
                        <div class="row mt-4">
                            <div class="col-md-12">
                                <div class="mb-3">
                                    <span>Item No.</span>
                                    <span style="border: 1px solid; margin-left: 10px; padding: 5px;">
                                        @foreach ($products as $product)
                                            {{ $bomTree['bom']['product_id'] == $product->id ? $product->part_no : '' }}
                                        @endforeach
                                    </span>
                                </div>
                                <div class="table-responsive">
                                    <table class="table table-bordered m-0">
                                        <thead>
                                            <tr>
                                                <th>Part No.</th>
                                                <th>Part Name</th>
                                                <th>Type</th>
                                                <th>Unit</th>
                                                <th>QTY</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @include('partials.bom_tree', ['bomTree' => $bomTree, 'units' => $units])
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    @endif
                @endforeach
            @endif
        </div>
    </div>


@endsection
