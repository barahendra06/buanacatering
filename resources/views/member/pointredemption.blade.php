@extends('layouts.app')

@section('htmlheader_title')
    {{ $title ?? '' }}
@endsection

@section('contentheader_title')
    {{  $title  ?? '' }}
@endsection

@push('content-header')
<style type="text/css">
	.prize-group .prize-item
	{
		/*border: solid 1px gray;*/
		padding-bottom: 15px;
		padding-top: 15px;
		position: relative;
	}
	.prize-item .icon-item
	{
		position: absolute;
		right: 15px;
		font-size: 70pt;
	    height: 100%;
	    bottom: 0;
		z-index: 1;
		color: rgba(0, 0, 0, 0.28);
	}
	.prize-item .body-item
	{
		z-index: 2;
		position: relative;
		height: 100%;
	}
	.prize-item .title
	{
		color: white;
		font-weight: 700;
		margin-top: 0;
	}
	.prize-item .requirement-point
	{
		color: white;
	}
	@media (min-width: 768px) {
		.prize-group
		{
			display: flex;
		}
	}
	.prize-item
	{
		position: relative;
	}
	.btn-redeem
	{
	    background-color: #2f2f2f;
	    border-color: #2f2f2f;
	    color: white;
	    font-weight: 700;
	    position: absolute;
	    bottom: 0;
	}
	.btn-redeem:hover,.btn-redeem:active
	{
	    color: #bbbbbb;
	}
</style>
@endpush

@section('main-content')
<div class="container-fluid">
	<hr>
	<div><div style="color: #f16868;font-weight: 600;"><i class="fa fa-warning"></i> You can redeem your remaining point on the first day of the month</div></div>
	<div class="row">
		<div class="col-sm-6">
			<br>
			<table>
				<tr>
					<td class="team-status-subject" width="120px"><b>Total Yearly Point</b></td>
					<td width="10px">:</td>
					<td>{{ $totalYearlyPoint }}</td>
				</tr>
				<tr>
					<td class="team-status-subject" width="120px"><b>Used Point</b></td>
					<td width="10px">:</td>
					<td>{{ $usedPoint }}</td>
				</tr>
				<tr>
					<td class="team-status-subject" width="120px"><b>Remaining Point</b></td>
					<td width="10px">:</td>
					<td>{{ $totalYearlyPoint-$usedPoint }}</td>
				</tr>
			</table>
		</div>
		<div class="col-sm-6">
		</div>
	</div>
	<div class="row">
		<div class="col-xs-12">
			<hr>
			<div class="row ">
				<div class="col-xs-12">
					<h4 style="font-weight:600"><i class="fa fa-star"></i> Prize</h4>
					@foreach( $prizes as $prize )
						@if(($loop->iteration+2)%3==0)
						<div class="prize-group">
						@endif
							<div class="col-sm-4 prize-item">
								<div class="icon-item"><i class="fa {{ REDEEM_PRIZE_ICON[$prize->id] }}"></i></div>
								<div class="body-item">
									<div>
										<h4 class="title">{{$prize->name}}</h4>
										<h5 class="requirement-point">{{$prize->point}} Points</h5>
									</div>
									<div style="height:30px"></div>
									@can('create', \App\RedemptionTransaction::class)
									<a class="btn btn-sm btn-redeem " onClick="redeemPrize({{ $prize->id }})">Redeem</a>
									@else
					                    <a class="btn btn-sm btn-redeem redeemPointAlert" style="cursor:pointer">Redeem</a>

						                @push('content-footer')
						                <script type="text/javascript">
						                $('.redeemPointAlert').click(function(){
						                    @if(\Carbon\Carbon::now()->gt(\Carbon\Carbon::now()->day(REDEMPTION_DAY)))
						                        var date = "{{ \Carbon\Carbon::now()->day(REDEMPTION_DAY)->addMonth()->format('l, d M Y') }}";
						                    @else
						                        var date = "{{ \Carbon\Carbon::now()->day(REDEMPTION_DAY)->format('l, d M Y') }}";
						                    @endif

						                    swal({
						                        title: "Caution",
						                        text: "You can redeem your point on " + date,
						                        type: "warning",
						                        closeOnConfirm: true
						                    });
						                });
						                </script>
						                @endpush
									@endcan
								</div>
							</div>
							@if($loop->iteration%3==0)
							<div class="clearfix"></div>
							@endif
						@if(($loop->iteration)%3==0)
						</div>
						@endif
					@endforeach
				</div>
			</div>
		</div>
	</div>
</div>
@endsection

@section('content-script')
<script type="text/javascript">
	function redeemPrize(prizeId) 
	{
		swal({
			title: "Are you sure?",
			text: "You want to redeem your point"+
				  "<div style='color: #c54f4f;font-size: 10.5pt;border: solid 1px;margin: auto;margin-top: 10px;line-height: 19px;width: 270px;padding: 10px;border-radius:10px'>"+
				      "This redemption has quota for each prize. If prize redemption has exceeded its quota, we will raffle all redemptions."+
				  "</div>",
			type: "warning",
			html: true,
			showCancelButton: true,
			confirmButtonText: "Yes, sure!",
			closeOnConfirm: false,
			showLoaderOnConfirm: true,
		},
		function(){
			window.location.href = "{{ route('redeem-point', [$member_id, null]) }}/"+prizeId;
		});
	}
	$(function(){
		var color = ['rgba(25, 7, 85, 0.5)','rgba(161, 18, 199, 0.5)','rgba(212, 65, 85, 0.5)','rgba(173, 74, 66, 0.5)','rgba(29, 144, 176, 0.5)','rgba(60, 31, 200, 0.5)'];
		for (var i = 0; i < $('.prize-item').length; i++) {
			$($('.prize-item')[i]).css('background', color[(i%6)]);
		};
		
	})
	
</script>
@endsection

