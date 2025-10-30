<table width="100%" border="0" cellspacing="0" cellpadding="0">
    <tr>
        <td align="center">
			{{ $employee->name }} ({{ $employee->employee_number }})
        </td>
    </tr>
    <tr>
        <td align="center">
            <img src="{{ secure_asset($employee->photo_path) }}" >
        </td>
    </tr>
</table>

