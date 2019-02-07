@extends('layouts.app')

@section('content')



<body>
<container>
    <div class="row">
        <div class="col-lg-4"
             <div>
                <h1> Cities with business </h1>
                <div> 

                    <table class="table table-bordered table-striped table-condensed">  
                        <tr>
                            <td colspan="3">  
                                {!! Form::open(['url' => 'addcommunebiz']) !!}
                                {!! Form::label('insee', 'Code INSEE : ') !!}                           
                                {!! Form::text('insee') !!}
                                {!! Form::submit('Add', ['class'=> 'btn btn-primary']) !!}
                                {!! Form::close() !!}
                            </td>
                            @if (isset($codesInseeBiz))
                            @foreach ($codesInseeBiz as $key=> $codeInseeBiz)
                        <tr>
                            <td> {{$codeInseeBiz}} </td>
                            <td> {{$nomCommBiz[$key]}} </td>
                            <td> <a href="deleteBiz/{{$codeInseeBiz}}">Delete </a> </td>

                        </tr>
                        @endforeach
                        @endif
                    </table>
                </div>
            </div>

            <div class="col-lg-4"
                 <div>
                    <h1> Cities with opportunities </h1>
                    <div> 

                        <table class="table table-bordered table-striped table-condensed">  
                            <tr>
                                <td colspan="3">  
                                    {!! Form::open(['url' => 'addcommuneopport']) !!}
                                    {!! Form::label('insee', 'Code INSEE : ') !!}                           
                                    {!! Form::text('insee') !!}
                                    {!! Form::submit('Add', ['class'=> 'btn btn-primary']) !!}
                                    {!! Form::close() !!}
                                    
                                </td>
                                @if (isset($codesInseeOpport))
                                    @foreach ($codesInseeOpport as $key=> $codeInseeOpport)
                                <tr>
                                    <td> {{$codeInseeOpport}} </td>
                                    <td> {{$nomCommOpport[$key]}} </td>
                                    <td> <a href="deleteOpport/{{$codeInseeOpport}}">Delete </a> </td>

                                </tr>
                                    @endforeach
                                @endif
                        </table>
                    </div>

                </div>

                <div class="col-lg-4"
                     <div>
                        <h1> Neighboring Cities </h1>
                        <div class="col-lg-12"> 
                            <button type="button" id="update" class="btn btn-success btn-block">Update</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
</container>
</body>

<script type = "text/javascript">
            document.getElementById("update").onclick = function () {
        location.href = "seed";
    };
</script>


@stop