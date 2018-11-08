<?php
    $parameters = request()->input();
?>
<div class='col-md-2'>
        <div class="dropdown">
            <button class="btn btn-primary dropdown-toggle" type="button" data-toggle="dropdown">
                    <i class="fa fa-download" aria-hidden="true"></i> Download <i class="fa fa-caret-down" aria-hidden="true"></i>
            </button>
            <ul class="dropdown-menu" role="menu">
                        <li>
                            <a href='{{Route(Route::currentRouteName(), array_merge($parameters, ["download" => "xlsx"]))}}'>
                                <i class="fa fa-file-excel-o" aria-hidden="true"></i> Microsoft Excel 2007 (XLSX)
                            </a>
                        </li>
                        <li>
                            <a href='{{Route(Route::currentRouteName(), array_merge($parameters, ["download" => "xls"]))}}'>
                                <i class="fa fa-file-excel-o" aria-hidden="true"></i> Microsoft Excel (XLS)
                            </a>
                        </li>
                        <li>
                            <a href='{{Route(Route::currentRouteName(), array_merge($parameters, ["download" => "csv"]))}}'>
                                <i class="fa fa-table" aria-hidden="true"></i> Comma-separated values (CSV)
                            </a>
                        </li>
            </ul>
        </div>
</div>