<?php namespace App\Http\Controllers;

use \Carbon\Carbon;

trait DateTrait {
	
	protected $monthNames = array('Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember');
	
	//function to change the date format YYYY-MM-DD into tanggal format DD-MM-YYYY
	public static function date2Tanggal($date)
	{
		return Carbon::createFromFormat('Y-m-d', $date)->format('d-m-Y');
	}

	//function to change the tanggal format YYYY-MM-DD into date format DD-MM-YYYY
	public static function tanggal2Date($tanggal)
	{
		return Carbon::createFromFormat('d-m-Y', $tanggal)->format('Y-m-d');
	}
}
