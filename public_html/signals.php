<header><i class="fa fa-bars fa-fw"></i> Daily Forex Signal</header>
<article>
    <script>
        signal.getSignals('sig');
        $(document).ready(function() {setInterval(function(){signal.getSignals('sig');}, 10000);});
    </script>
    <small>
        <em><strong>Date:</strong></em>
        <em id="table_date">
            <script>
                $(document).ready(function() {setInterval(function(){signal.get_date('table_date');}, 10000);});
            </script>
        </em>
        <em class="pull-right" id="table_time">
            <script>
                $(document).ready(function() {setInterval(function(){signal.get_time('table_time');}, 1000);});
            </script>
        </em>
    </small>
    <table style="font-size: 0.8em; font-family: sans-serif;" class="table table-responsive table-hover">
        <thead><tr><th></th><th></th><th></th><th></th><th></th></tr></thead>
        <tbody id="sig"></tbody>
    </table>
    <hr>
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
                    <div class="row">
                        <div class="col-sm-4"><b id="win_date" class="pull-left"></b></div>
                        <div class="col-sm-4"><h4><center>Trading Signal</center></h4></div>
                        <div class="col-sm-4"><b id="win_time" class="pull-right"></b></div>
                    </div>
                </div>
                <script>
                    $(document).ready(function() {setInterval(function(){signal.get_date('win_date');}, 10000);});
                    $(document).ready(function() {setInterval(function(){signal.get_time('win_time');}, 1000);});
                </script>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-sm-12">
                        <div class="col-sm-2">
                            <h4 style="color: green"><b><i class="glyphicon glyphicon-arrow-up"></i></b></h4>
                        </div>
                        <div class="col-sm-5">
                            <h4 style="color: black"><b>SELL ORDER</b></h4>
                        </div>
                        <div class="col-sm-5">
                            <h4 style="color: black"><b>PRICE: 123.09</b></h4>
                        </div>
                    </div>

                    <div class="col-sm-12">
                        <div class="col-sm-12">
                            <table class="table table-bordered table-responsive">
                                <thead><tr><th></th><th></th></tr></thead>
                                <tbody>
                                    <tr><td><b>TAKE PROFIT</b></td><td>0.67</td></tr>
                                    <tr><td><b>STOP LOSS</b></td><td>0.67</td></tr>
                                    <tr><td><b>TRIGGER DATE & TIME</b></td><td>14 May 2018 12:36:00 PM</td></tr>
                                    <tr><td><b>KEYNOTE</b></td><td>This course is designed to take a beginner by the hand, step by step to become a professional and profitable trader.</td></tr>
                                </tbody>
                            </table>
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