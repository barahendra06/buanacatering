<div>
    <select class="form-control" name="invoice_type">
        <option value="">All Type</option>
        @foreach($types as $type)
            <option value="{{ $type->id }}" {{ (int)$value === $type->id ? 'selected' : '' }}>{{ $type->name }}</option>
        @endforeach
    </select>
</div>