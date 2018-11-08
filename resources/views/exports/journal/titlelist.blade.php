<html>
        <table>
        <tr><td><h1>Journals - Export Titles</h1></td></tr>
        <tr><td>Journal ID</td><td>Electronic ISSN</td><td>Print ISSN</td><td>Title</td>
        @foreach($journals->get() as $journal)
            <tr>
                <td>{{$journal->id}}</td>
                <td>{{$journal->e_issn}}</td>
                <td>{{$journal->p_issn}}</td>
                <td>{{$journal->journal_title}}</td>
            </td>
        @endforeach
        </table>
    </html>