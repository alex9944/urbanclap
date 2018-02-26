@extends('layouts.adminmain')

@section('content')
        <section id="main-content">
            <section class="wrapper">

                <div class="content-box-large">
                    <h1>View Categories</h1>


                    <table id="datatable-checkbox" class="table table-striped table-bordered bulk_action">
                        <thead>  <tr>
                                <th>Category ID</th>
                                <th>Category Name</th>
                                <th>Status</th>
                                <th>update</th>
                                <th>Delete</th>
                            </tr>
                        </thead>
                        @foreach($cats as $cat)
                        <tbody>
                            <tr>
                                <td>{{$cat->id}}</td>
                                <td>{{$cat->name}}</td>
                                <td>@if($cat->status=='0')
                                    Enable
                                    @else
                                    Disable

                                    @endif</td>
                                <td><a href="{{url('/')}}/admin/CatEditForm/{{$cat->id}}" class="btn btn-info btn-xs"><i class="fa fa-pencil"></i>Edit</a></td>
                                <td><a href="{{url('/admin/deleteCat')}}/{{$cat->id}}" class="btn btn-danger btn-xs"><i class="fa fa-trash-o"></i>Remove</a></td>
                            </tr>
                        </tbody>
                        @endforeach
                    </table>

                </div>



        </section>
      </section>
<div class="clearfix"></div>

@endsection
