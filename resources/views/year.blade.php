@extends('layouts.master')

@section('title')
    Federal Tax Estimator
@endsection

@section('content')
    <header class="row-fluid">
        <h1 id="banner"><i class="fa fa-usd red" aria-hidden="true"></i> Standard Deduction Tax Estimator <i class="fa fa-usd green" aria-hidden="true"></i></h1>
    </header>
    
    <div class="container-fluid">
    	<div id="content" class="row-fluid">
            <form action="/taxyear" method='get' name="yearForm">
                <fieldset id="year">
                    <legend>Select Tax Year</legend>
                    <span class="require-symbol" title="Required">* </span><select name="year" required>
                        <option disabled selected value>Select Tax Year</option>
                        <option value="2016">2016</option>
                        <option value="2017">2017</option>
                    </select>
                </fieldset>
                
                <div class="text-center">
                    <input type="submit" name="action" value="Submit" class="btn btn-lg"/>
                </div>

            </form>
            
            @if(count($errors) >0)
                <ul class="alert alert-danger">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            @endif

        </div>
    </div>
@endsection