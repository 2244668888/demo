@foreach ($bomTree['purchaseParts'] as $purchasePart)
    <tr>
        <td>{{ $purchasePart->product->part_no }}</td>
        <td>{{ $purchasePart->product->part_name }}</td>
        <td>Material and Purchase Part</td>
        <td>
            @foreach ($units as $unit)
                @if ($unit->id ==  $purchasePart->product->unit)
                    {{ $unit->name }}
                @endif
            @endforeach
        </td>
        <td>{{ $purchasePart['qty'] }}</td>
    </tr>
@endforeach

@foreach ($bomTree['crushings'] as $crushing)
    <tr>
        <td>{{ $crushing->product->part_no }}</td>
        <td>{{ $crushing->product->part_name }}</td>
        <td>Crushing Material</td>
        <td>
            @foreach ($units as $unit)
                @if ($unit->id ==  $crushing->product->unit)
                    {{ $unit->name }}
                @endif
            @endforeach
        </td>
        <td></td>
    </tr>
@endforeach

@foreach ($bomTree['subParts'] as $subpart)
    <tr>
        <td>{{ $subpart['subPart']->product->part_no }}</td>
        <td>{{ $subpart['subPart']->product->part_name }}</td>
        <td>Sub Part</td>
        <td>
            @foreach ($units as $unit)
                @if ($unit->id ==  $subpart['subPart']->product->unit)
                    {{ $unit->name }}
                @endif
            @endforeach
        </td>
        <td>
            @if (!empty($subpart['bomTree']))
                <button class="accordion-button" type="button" data-bs-toggle="collapse"
                data-bs-target="#collapse{{ $subpart['subPart']->id }}" aria-expanded="true"
                aria-controls="collapse{{ $subpart['subPart']->id }}">{{ $subpart['subPart']->qty }} <i class="fs-3 bi bi-link-45deg"></i>
                </button>
            @else
                {{ $subpart['subPart']->qty }}
            @endif
        </td>
    </tr>
    @if (!empty($subpart['bomTree']))
        <tr id="collapse{{ $subpart['subPart']->id }}" class="accordion-collapse collapse"
        aria-labelledby="heading{{ $subpart['subPart']->id }}" data-bs-parent="#bomTable">
            <td colspan="6">
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
                        @include('partials.bom_tree', ['bomTree' => $subpart['bomTree'], 'units' => $units])
                    </tbody>
                </table>
            </td>
        </tr>
    @endif
@endforeach
