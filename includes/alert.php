<style>
    html {
        width: 100%;
        height: 100%;
        overflow: hidden
    }

    body {
        width: 100%;
        max-height: 500px;
        padding: 125px 0;
        background: rgb(244, 226, 156);
        background: -moz-linear-gradient(-45deg, rgba(244, 226, 156, 0) 0%, rgba(59, 41, 58, 1) 100%), -moz-linear-gradient(left, rgba(244, 226, 156, 1) 0%, rgba(130, 96, 87, 1) 100%);
        background: -webkit-linear-gradient(-45deg, rgba(244, 226, 156, 0) 0%, rgba(59, 41, 58, 1) 100%), -webkit-linear-gradient(left, rgba(244, 226, 156, 1) 0%, rgba(130, 96, 87, 1) 100%);
        background: -o-linear-gradient(-45deg, rgba(244, 226, 156, 0) 0%, rgba(59, 41, 58, 1) 100%), -o-linear-gradient(left, rgba(244, 226, 156, 1) 0%, rgba(130, 96, 87, 1) 100%);
        background: -ms-linear-gradient(-45deg, rgba(244, 226, 156, 0) 0%, rgba(59, 41, 58, 1) 100%), -ms-linear-gradient(left, rgba(244, 226, 156, 1) 0%, rgba(130, 96, 87, 1) 100%);
        background: linear-gradient(135deg, rgba(244, 226, 156, 0) 0%, rgba(59, 41, 58, 1) 100%), linear-gradient(to right, rgba(244, 226, 156, 1) 0%, rgba(130, 96, 87, 1) 100%);
        font-family: "HelveticaNeue-Light", "Helvetica Neue Light", "Helvetica Neue", Helvetica, Arial, "Lucida Grande", sans-serif;
        font-weight: 300;
    }

    p {
        font-size: 13px;
        margin: 0;
        padding: 0;
        line-height: 40px;
        color: #fff;
        text-shadow: 0 -1px 0 rgba(0, 0, 0, 1);
        border-bottom: 1px solid rgba(0, 0, 0, 0.3);
        box-shadow: 0 1px 1px rgba(255, 255, 255, 0.2);
        letter-spacing: 1px;
    }

    .notification_box {
        width: 260px;
        height: 160px;
        margin: 0 auto;
        border-radius: 5px;
        background: rgba(0, 0, 0, 0.55);
        border: 1px solid rgba(0, 0, 0, 0.2);
        text-align: center;
        box-shadow: 0 0 5px rgba(0, 0, 0, 0.5), 0 2px 2px rgba(0, 0, 0, 0.1);
    }

    .inner {
        width: 260px;
        height: 160px;
        border-radius: 5px;
        background: linear-gradient(top, rgba(239, 239, 239, 0.1) 0%, rgba(0, 0, 0, 0.3) 100%);
        box-shadow: inset 0 8px 10px rgba(255, 255, 255, 0.1), inset 0 1px 2px rgba(255, 255, 255, 0.3), inset 0 1px 0 rgba(255, 255, 255, 0.2), inset 0 -1px 0 rgba(255, 255, 255, 0.1);
    }

    .btn {
        margin-top: 20px;
        width: 80px;
        height: 40px;
        line-height: 40px;
        display: inline-block;
        border-radius: 4px;
        border: 1px solid rgba(0, 0, 0, 0.7);
        box-shadow: inset 0 4px 6px rgba(255, 255, 255, 0.3), inset 0 0 2px rgba(255, 255, 255, 0.3), 0 2px 2px rgba(0, 0, 0, 0.15);
        -webkit-transition: all 0.3s ease;
    }

    .btn-accept {
        background-color: rgb(100, 142, 49);
        background-image: -moz-linear-gradient(top, rgba(100, 142, 49, 1) 0%, rgba(57, 93, 15, 1) 100%);
        background-image: -webkit-gradient(linear, left top, left bottom, color-stop(0%, rgba(100, 142, 49, 1)), color-stop(100%, rgba(57, 93, 15, 1)));
        background-image: -webkit-linear-gradient(top, rgba(100, 142, 49, 1) 0%, rgba(57, 93, 15, 1) 100%);
        background-image: -o-linear-gradient(top, rgba(100, 142, 49, 1) 0%, rgba(57, 93, 15, 1) 100%);
        background-image: -ms-linear-gradient(top, rgba(100, 142, 49, 1) 0%, rgba(57, 93, 15, 1) 100%);
        background-image: linear-gradient(to bottom, rgba(100, 142, 49, 1) 0%, rgba(57, 93, 15, 1) 100%);
        margin-right: 10px;
    }

    .btn-accept:hover {
        background: rgba(100, 142, 49, 1);
    }

    .btn-accept:active {
        background: rgba(57, 93, 15, 1);
        box-shadow: inset 0 0 3px rgba(0, 0, 0, 0.5);
    }

    .btn-decline {
        background-color: rgb(203, 90, 90);
        background-image: -moz-linear-gradient(top, rgba(203, 90, 90, 1) 0%, rgba(141, 62, 62, 1) 100%);
        background-image: -webkit-gradient(linear, left top, left bottom, color-stop(0%, rgba(203, 90, 90, 1)), color-stop(100%, rgba(141, 62, 62, 1)));
        background-image: -webkit-linear-gradient(top, rgba(203, 90, 90, 1) 0%, rgba(141, 62, 62, 1) 100%);
        background-image: -o-linear-gradient(top, rgba(203, 90, 90, 1) 0%, rgba(141, 62, 62, 1) 100%);
        background-image: -ms-linear-gradient(top, rgba(203, 90, 90, 1) 0%, rgba(141, 62, 62, 1) 100%);
        background-image: linear-gradient(to bottom, rgba(203, 90, 90, 1) 0%, rgba(141, 62, 62, 1) 100%);
        margin-left: 10px;
    }

    .btn-decline:hover {
        background: rgba(203, 90, 90, 1);
    }

    .btn-decline:active {
        background: rgba(141, 62, 62, 1);
        box-shadow: inset 0 0 3px rgba(0, 0, 0, 0.5);
    }


    .icon {
        background: url(https://s.cdpn.io/6035/notification_icons.png) no-repeat;
        display: inline-block;
        margin-top: 12px;
    }

    .icon-arrow {
        width: 19px;
        height: 15px;
        background-position: 0 0;
    }

    .icon-x {
        width: 15px;
        height: 15px;
        background-position: -19px 0;
    }
</style>
<div class="notification_box">
    <div class="inner">
        <form method="POST">
            <p class="text-danger">It is inform you that your currenct balance is below(0) and going to -ve, do you wish to proceed with current entry?</p>
            <button type="submit" name="saveAlert" id="save" class="btn btn-accept"><span class="icon icon-arrow"></span></button>
            <button type="submit" name="cancelAlert" id="cancel" class="btn btn-decline"><span class="icon icon-x"></span></button>
        </form>
    </div>
</div>