<?php
defined('BASEPATH') OR exit('No direct script access allowed');

	class Dates
	{
	
		public function __construct()
		{
			
			
		}
		/*
		* Input Date Format is // DD/MM/YY
		*/
		public function DATE_YY_MM_DD($date)
		{
			//$date=date_create("2013-03-15");
			return date_format($date,"Y-m-d");
		}

		/*
		* Input Date Format is // yy-mm-dd
		*/
		public function DATE_DAY_MONTH_YEAR($date)
		{
			$timestamp = strtotime($date);
			$day = date('l', $timestamp);
			$daynumber = date('j', $timestamp);
			$month = date('F', $timestamp);
			$year = date('Y', $timestamp);
			$subpre = date('S', $timestamp);
			$dateformat = $day.",&nbsp;".$month."&nbsp;".$daynumber."".$subpre.",&nbsp;".$year;
			return $dateformat;
		}
		/*
		* Input Date Format is // yy-mm-dd
		*/
		public function DATE_MONTH_DAY_YEAR($date)
		{
			$timestamp = strtotime($date);
			$day = date('l', $timestamp);
			$daynumber = date('j', $timestamp);
			$month = date('F', $timestamp);
			$monthnumber = date('m', $timestamp);
			$year = date('Y', $timestamp);
			$subpre = date('S', $timestamp);
			$dateformat = $monthnumber."/".$daynumber."/".$year;
			return $dateformat;
		}
		/*
		* Input Date Format is // yy-mm-dd
		*/
		public function DATE_YY_MM_DD_ARRAY($date)
		{	
			$array = array();
			$timestamp = strtotime($date);
			$array['year'] = date('Y', $timestamp);
			$array['month'] = date('m', $timestamp);
			$array['day'] = date('j', $timestamp);
			return $array;
		}
		public function GetDateTimeInMinutes($startDate="",$endDate="")
		{
			print $startDate." ";
			print $endDate." ";
			$bstrtime = strtotime($endDate);
			$cstrtime = strtotime($startDate);
			$time = ($bstrtime - $cstrtime);
			$minutes = round(($time /3600 / 60),0,PHP_ROUND_HALF_DOWN);
			return $minutes;
		}
	}
	