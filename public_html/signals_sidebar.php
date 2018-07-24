<?php $signal_object = new Signal_Management();?>
<header><i class="fa fa-bars fa-fw"></i> Daily Forex Trading Signal</header>
<article>
    <script>
        signal.getSignals('sig');
        $(document).ready(function() {setInterval(function(){signal.getSignals('sig');}, 3600000);});
    </script>
    <small>
        <em><strong>Date:</strong></em>
        <em id="table_date">
            <script>
                $(document).ready(function() {setInterval(function(){signal.get_date('table_date');}, 1000);});
            </script>
        </em>
        <em style="margin:10px;">    </em>
        <em id="table_time">
            <script>
                $(document).ready(function() {setInterval(function(){signal.get_time('table_time');}, 1000);});
            </script>
        </em>
        <em  class="pull-right">
            <button id="page_reloader_side" style="display: none; border: 0px;border-radius:50%;" title="Refresh Signals" class="btn btn-success btn-sm" ><i class="glyphicon glyphicon-refresh"></i></button>
        </em>
    </small>
    <hr>
    <small><span class="glyphicon glyphicon-exclamation-sign"></span> Click on each signal Status for Details and Guide. </small>
    <br>
    <table style="font-size: 0.8em; font-family: sans-serif;" class="table table-responsive table-hover">
        <thead><tr><th></th><th></th><th></th><th></th><th></th></tr></thead>
        <tbody>
        <?php echo $signal_object->UI_get_signals_for_sidebar();?>
<!--        <a href="https://instafxng.com"><span><tr><td colspan="5"><center><h4><strong href="https://instafxng.com"><span class="glyphicon glyphicon-refresh glyphicon-refresh-animate"></span> Loading...</strong></h4></center></td></tr></span></a>-->
<!--        -->
        </tbody>
    </table>
    <a class="btn btn-group-justified btn-success btn-xs" href="signal_schedules.php">View More</a>
    <hr>
    <br>
    <small style="font-size: x-small">Your use of the signals means you have read and accepted our
        <a href="signal_terms_of_use.php" title="Forex Signal Terms of Use">terms of use</a>.
        Download the <a href="downloads/signalguide.pdf" target="_blank" title="Download signal guide">
            signal guide</a> to learn how to use the signals.
    </small>

</article>
<div id="signal_display" tabindex="-1" role="dialog" aria-hidden="true" class="modal fade">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" data-dismiss="modal" aria-hidden="true"  class="close">&times;</button>
                <div class="modal-title">
                    <h4><center>Trading Signal</center></h4>
                    <!--<tr>
                        <td><center><b style="font-size: large" id="win_date"></b><center></td>
                        <td></td>
                        <td><center><b style="font-size: large" id="win_time"></b><center></td>
                        <script>
                            $(document).ready(function() {setInterval(function(){signal.get_date('win_date');}, 10000);});
                            $(document).ready(function() {setInterval(function(){signal.get_time('win_time');}, 1000);});
                        </script>
                    </tr>-->
                </div>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-sm-12">
                        <div class="col-sm-12">
                            <div id="preloader">
                                <center>
                                    <h4><b><span class="glyphicon glyphicon-refresh glyphicon-refresh-animate"></span> Loading...</b></h4>
                                </center>
                            </div>
                            <div id="sig_content">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" name="close" onClick="window.close();" data-dismiss="modal" class="btn btn-sm btn-danger">Close!</button>
            </div>
        </div>
    </div>
</div>
<script>
    signal.new_signal_listener();
</script>