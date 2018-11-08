<html>
    <table>
    <tr><td><h2>Report Metadata</h2></td></tr>
    <tr><td><h2>Auto-Generated Report Exported by Dalhousie Journal Assessment Database</h2></td></tr>
    <tr><td><h2>Produced By:</h2></td><td>@if(auth()->user()){{auth()->user()->name}}@endif</td></tr>
    <tr><td><h2>Date Retrieved:</h2></td><td>{{\Carbon\Carbon::now()}}</td></tr>
    <tr><td><h2>Report Requested:</h2></td><td>{{$report_name}}</td></tr>
    <tr><td><h2>Format Requested:</h2></td><td>{{$format_type}}</td></tr>
    <tr><td><h2>Records Exported:</h2></td><td>{{$record_count}}</td></tr>
    <tr><td><h2>Automatically Importable:</h2></td><td>{{$importable}}</td></tr>
    <tr><td><h2>Query Criteria:</h2></td><td>{{$query}}</td></tr>
    <tr><td><h2>Generation URL:</h2></td><td>{{$url}}</td></tr>
    </table>
</html>