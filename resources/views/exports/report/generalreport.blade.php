<html>
    <table>
        <tr><td><h1>Report Export:</h1></td><td><h1>{{$report_title}}</h1></td></tr>
        <tr><td>Journals Exported:</td><td>{{count($journals)}}</td></tr>
        <tr>
            @foreach($headers as $header)
            <td>
                {{$header}}
            </td>
            @endforeach
        </tr>
        @foreach($journals as $journal)
        <tr>
            
            @foreach($journal as $data)
            <td>
                {{$data}}
            </td>
            @endforeach
        </tr>
        @endforeach
    </table>
</html>