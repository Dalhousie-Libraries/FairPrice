            <h4>
            <b>List of Journals - Page {{$journals->currentPage()}} of {{$journals->lastPage()}}</b>
            <a class="btn btn-primary btn-sm" style='float:right' href='{{$journals->url($journals->lastPage())}}'>>>|</a>
            <a class="btn btn-primary btn-sm" style='float:right' href='{{$journals->nextPageUrl()}}'>>></a>
            <a class="btn btn-primary btn-sm" style='float:right' href='{{$journals->previousPageUrl()}}'><<</a>
            <a class="btn btn-primary btn-sm" style='float:right' href='{{$journals->url(1)}}'>|<<</a>
            
            </h4>