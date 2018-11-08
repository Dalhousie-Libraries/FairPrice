<html>
    <table>
    <tr>
        <td>Journal ID</td>
        <td>Print ISSN</td>
        <td>Online ISSN</td>
        <td>JUP id</td>
        <td>Proprietary Identifier</td>
        <td>Package</td>
        <td>Journal DOI</td>
        <td>URL</td>
        <td>Title</td>
        <td>General Notes</td>
        <td>All_Titles</td>
        <td>Publisher</td>
        <td>Journal Status</td>
        <td>Discipline</td>
        <td>Libraries</td>
        <td>Faculties</td>
        <td>Departments</td>
        <td>Downloads</td>
        <td>Citations</td>
        <td>Needed</td>
        <td>Wanted</td>
        <td>Not Needed</td>
        <td>Needed (Faculty Only)</td>
        <td>Wanted (Faculty Only)</td>                
        <td>Not Needed (Faculty Only)</td>        
        <td>Prices</td>
        <td>Post-Cancellation Rights</td>
        <td>Post-Cancellation Begin</td>
        <td>Package Coverage</td>
        <td>Alternate Current or Embargo Access</td>
        <td>Type of Alternate</td>
        <td>Shortest Embargo</td>
        @foreach($platforms as $platform)
            <td>{{$platform->name}} Coverage</td>
            <td>{{$platform->name}} Embargo</td>
            <td>{{$platform->name}} Embargo Length</td>
        @endforeach
    </tr>
    @foreach($journals->get() as $journal)
        <tr>
            <td>{{$journal->id}}</td>
            <td>{{$journal->p_issn}}</td>
            <td>{{$journal->e_issn}}</td>
            <td>{{$journal->jup}}</td>
            <td>{{$journal->doi}}</td>
            <td>{{$journal->journal_title}}</td>
            <td>{{$journal->abbreviation}}</td>
            <td>{{$journal->proprietary_identifier}}</td>
            <td>{{$journal->url}}</td>
            <td>
                @if($journal->subject_1)

                @else
                
                @endif
            </td>
            @foreach($platforms as $platform)
                <td>{{$platform->name}} Coverage</td>
                <td>{{$platform->name}} Embargo</td>
                <td>{{$platform->name}} Embargo Length</td>
            @endforeach
        </tr>
    @endforeach
    </table>
</html>