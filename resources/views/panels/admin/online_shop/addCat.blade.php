@extends('layouts.adminmain')

@section('content')
        <section id="main-content">
            <section class="wrapper">


                <div class="content-box-large">
                    <h1>Add Category</h1>

                    {!! Form::open(['url' => 'admin/catForm',  'method' => 'post']) !!}
                    <table class="table-borderless" style="height:200px">

                        <tr>
                            <td> Catgeory Name:</td>
                            <td>    <input type="text" name="cat_name" class="form-control"></td>
                        </tr>
						<tr>
                            <td> Catgeory Status:</td>
                            <td>
                                <select name="status" class="form-control">
                                    <option value="0" selected="selected">Enable</option>
                                    <option value="1">Disable</option>

                                </select>
                            </td>
                        </tr>

                         <tr>
                             <td colspan="2">
                        <input type="submit" value="Add Category" class="btn btn-success pull-right">
                             </td>
                         </tr>
                        <input type="hidden" name="_token" value="{{ csrf_token() }}">

                        {!! Form::close() !!}
                    </table>
                </div>

            </section>
      </section>
<div class="clearfix"></div>  

@endsection
