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
                <h4 class="modal-title"><span id="win_date" class="pull-left"></span> <span class="text-center">Trading Signal</span> <span id="win_time" class="pull-right"></span></h4>
                <script>
                    $(document).ready(function() {setInterval(function(){signal.get_date('win_date');}, 10000);});
                    $(document).ready(function() {setInterval(function(){signal.get_time('win_time');}, 1000);});
                </script>
            </div>
            <div class="modal-body">

            </div>
            <div class="modal-footer">
                <input name="process" type="submit" class="btn btn-success" value="Proceed">
                <button type="button" name="close" onClick="window.close();" data-dismiss="modal" class="btn btn-danger">Close!</button>
            </div>
        </div>
    </div>
</div>