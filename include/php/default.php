<?php
$title = "Homepage";
$include_header = '<link href="./include/css/plugins/footable/footable.core.css" rel="stylesheet">';
$include_footer = '  <!-- FooTable -->
    <script src="./include/js/plugins/footable/footable.all.min.js"></script>
    <script>



          //Attempt to load JSON Data using jQuery
    function updateNewsFeed(){
          $.getJSON("./include/json/projects.json", function(data){

              $("#newsfeed").html("");

                    for (var i = 0, len = data.length; i < len; i++) {

                      var line = "<tr>";
                      line += "<td>"+data[i].title+"</td>";
                      line += "<td>"+data[i].publisher+"</td>";
                      line += "<td>"+data[i].date+"</td>";
                      line += "<td>"+data[i].readers+"</td>";
                      line += "<td>"+data[i].desc+"</td>";
                      line += "<td>"+data[i].date+"</td>";
                      line += `<td><a href="#" class="btn btn-sm btn-primary">Remove from Timeline</a></td>`;
                      line += "</tr>";
                    //  var line = "<tr>td>Project - This is example of project</td><td>Patrick Smith</td><td>0800 051213</td><td>Inceptos Hymenaeos Ltd</td><td><span class=\"pie\">0.52/1.561</span></td><td>20%</td><td>Jul 14, 2013</td><td><a href=\"#\"><i class=\"fa fa-check text-navy\"></i></a></td></tr>";
                      $("#newsfeed").append(line);

                    }


  });
}

//Call news feed update first time
updateNewsFeed();
setInterval(function(){
updateNewsFeed();
setTimeout(function() {
toastr.options = {
closeButton: true,
positionClass: "toast-bottom-right",
progressBar: true,
showMethod: \'slideDown\',
timeOut: 1500
};
toastr.warning(\'Refreshing News Feed..\', \'iScience+ News Feed\');

}, 0);
}, 20000);





                    $.getJSON("./include/json/jobs.json", function(data){



                              for (var i = 0, len = data.length; i < len; i++) {

                                var line = "<tr>";
                                line += "<td>"+data[i].Role+"</td>";
                                line += "<td>"+data[i].Company+"</td>";
                                line += "<td>"+data[i].date+"</td>";
                                line += "<td>"+data[i].Description+"</td>";
                                line += `<td><a href="#" class="btn btn-sm btn-primary">Apply</a></td>`;
                                line += "</tr>";
                              //  var line = "<tr>td>Project - This is example of project</td><td>Patrick Smith</td><td>0800 051213</td><td>Inceptos Hymenaeos Ltd</td><td><span class=\"pie\">0.52/1.561</span></td><td>20%</td><td>Jul 14, 2013</td><td><a href=\"#\"><i class=\"fa fa-check text-navy\"></i></a></td></tr>";
                                $("#jobhunt").append(line);
                  }

                  setTimeout($(\'.footable\').footable(), 500);
                  setTimeout($(\'.footable2\').footable(), 501);


                              });



       </script>';

$addArticle = '
<div class="input-group">
    <input type="text" placeholder="Add a new project" class="input input-sm form-control">
    <span class="input-group-btn">
            <button type="button" class="btn btn-sm btn-white"> <i class="fa fa-plus"></i> Post Project</button>
    </span>
</div>
';


$content = '<div class="row">
                <div class="col-lg-12">
                    <div class="ibox float-e-margins">
                        <div class="ibox-title">
                            <h5>iScience News Feed</h5>

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
                        '.$addArticle.'
                        <div class="ibox-content">

                            <table class="footable table table-stripped toggle-arrow-tiny" data-page-size="20">
                                <thead>
                                <tr>

                                    <th data-toggle="true">Project</th>
                                    <th>Name</th>
                                    <th>Date Published</th>
                                    <th data-hide="all">Readers</th>
                                    <th data-hide="all">Description</th>
                                    <th data-hide="all">Date</th>
                                    <th>Action</th>
                                </tr>
                                </thead>
                                <tbody id ="newsfeed">

                                </tbody>
                                <tfoot>
                                <tr>
                                    <td colspan="5">
                                        <ul class="pagination pull-right"></ul>
                                    </td>
                                </tr>
                                </tfoot>
                            </table>

                        </div>
                        <div class="ibox float-e-margins">
                            <div class="ibox-title">
                                <h5>iScience Job Hunt Area</h5>

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

                            <table class="footable table table-stripped toggle-arrow-tiny" data-page-size="4">
                                <thead>
                                <tr>

                                    <th data-toggle="true">Job Role</th>
                                    <th>Company Hiring</th>
                                    <th>Date Published</th>
                                    <th data-hide="all">Description</th>
                                    <th>Action</th>
                                </tr>
                                </thead>
                                <tbody id ="jobhunt">

                                </tbody>
                                <tfoot>
                                <tr>
                                    <td colspan="5">
                                        <ul class="pagination pull-right"></ul>
                                    </td>
                                </tr>
                                </tfoot>
                            </table>

                        </div>
                    </div>
                </div>
            </div>


';
?>
