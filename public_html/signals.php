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
        <tbody id="sig">
        <tr><td colspan="5"><center><h4><strong><span class="glyphicon glyphicon-refresh glyphicon-refresh-animate"></span> Loading...</strong></h4></center></td></tr>
        </tbody>
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

<div id="signal_display1" tabindex="-1" role="dialog" aria-hidden="true" class="modal fade">
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
                            <table class="table table-bordered table-responsive">
                                <tbody>
                                <tr>
                                    <td><b>ORDER</b></td>
                                    <td>SELL</td>
                                    <td rowspan="5">
                                        <center><b style="color: green!important; font-size: 150px"><i class="glyphicon glyphicon-arrow-up"></i></b></center>
                                    </td>
                                </tr>
                                <tr><td><b>PRICE</b></td><td>123.09</td></tr>
                                <tr><td><b>TAKE PROFIT</b></td><td>0.67</td></tr>
                                <tr><td><b>STOP LOSS</b></td><td>0.67</td></tr>
                                <tr><td><b>TRIGGER DATE & TIME</b></td><td>14 May 2018 12:36:00 PM</td></tr>
                                <tr><td><b>KEYNOTE</b></td><td colspan="2">This course is designed to take a beginner by the hand, step by step to become a professional and profitable trader.</td></tr>
                                <tr>
                                    <td><b>FEEDBACK</b></td>
                                    <td colspan="2">
                                        <input id="input-1" name="rating" class="rating rating-sm rating-loading rating-sm" data-min="0" data-max="5" data-step="1" required>
                                        <br/>
                                        <textarea placeholder="Comments (If Any)" rows="2" id="comments" name="comments" class="form-control"></textarea>
                                    </td>
                                </tr>
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