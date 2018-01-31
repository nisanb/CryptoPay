<?php

//This is the page title
$title = "Followers";

//Include CSS to Header
$include_header = '

    ';

//Include JS to Footer
$include_footer = '



';

//This is the page content
$content = '
<div class="col-lg-12">
                    <div class="ibox float-e-margins">
                        <div class="ibox-title">
                            <h5>FooTable with row toggler, sorting and pagination</h5>

                            <div class="ibox-tools">
                                <a class="collapse-link">
                                    <i class="fa fa-chevron-up"></i>
                                </a>
                                <a class="dropdown-toggle" data-toggle="dropdown" href="#">
                                    <i class="fa fa-wrench"></i>
                                </a>
                                <ul class="dropdown-menu dropdown-user">
                                    <li><a href="#">Config option 1</a>
                                    </li>
                                    <li><a href="#">Config option 2</a>
                                    </li>
                                </ul>
                                <a class="close-link">
                                    <i class="fa fa-times"></i>
                                </a>
                            </div>
                        </div>
                        <div class="ibox-content">

                            <table class="footable table table-stripped toggle-arrow-tiny footable-loaded tablet breakpoint" data-page-size="8">
                                <thead>
                                <tr>

                                    <th data-toggle="true" class="footable-visible footable-sortable footable-first-column">Project<span class="footable-sort-indicator"></span></th>
                                    <th class="footable-visible footable-sortable">Name<span class="footable-sort-indicator"></span></th>
                                    <th class="footable-visible footable-sortable">Phone<span class="footable-sort-indicator"></span></th>
                                    <th data-hide="all" class="footable-sortable" style="display: none;">Company<span class="footable-sort-indicator"></span></th>
                                    <th data-hide="all" class="footable-sortable" style="display: none;">Completed<span class="footable-sort-indicator"></span></th>
                                    <th data-hide="all" class="footable-sortable" style="display: none;">Task<span class="footable-sort-indicator"></span></th>
                                    <th data-hide="all" class="footable-sortable" style="display: none;">Date<span class="footable-sort-indicator"></span></th>
                                    <th class="footable-visible footable-sortable footable-last-column">Action<span class="footable-sort-indicator"></span></th>
                                </tr>
                                </thead>
                                <tbody>
                                <tr class="footable-even" style="display: table-row;" id="datatable">



                                </script>
                                    <td class="footable-visible footable-first-column"><span class="footable-toggle"></span>Project - This is example of project</td>
                                    <td class="footable-visible">Patrick Smith</td>
                                    <td class="footable-visible">0800 051213</td>
                                    <td style="display: none;">Inceptos Hymenaeos Ltd</td>
                                    <td style="display: none;"><span class="pie">0.52/1.561</span></td>
                                    <td style="display: none;">20%</td>
                                    <td style="display: none;">Jul 14, 2013</td>
                                    <td class="footable-visible footable-last-column"><a href="#"><i class="fa fa-check text-navy"></i></a></td>
                                </tr>

                                </tbody>
                                <tfoot>
                                <tr>
                                    <td colspan="5" class="footable-visible">
                                        <ul class="pagination pull-right"><li class="footable-page-arrow disabled"><a data-page="first" href="#first">«</a></li><li class="footable-page-arrow disabled"><a data-page="prev" href="#prev">‹</a></li><li class="footable-page active"><a data-page="0" href="#">1</a></li><li class="footable-page"><a data-page="1" href="#">2</a></li><li class="footable-page-arrow"><a data-page="next" href="#next">›</a></li><li class="footable-page-arrow"><a data-page="last" href="#last">»</a></li></ul>
                                    </td>
                                </tr>
                                </tfoot>
                            </table>

                        </div>
                    </div>
                </div>
';
?>
