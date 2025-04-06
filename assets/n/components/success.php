<link href="https://fonts.googleapis.com/css?family=Raleway&display=swap" rel="stylesheet">
<style>
    /* Styles for the success popup */
    .popup {
        display: none;
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background-color: rgba(0, 0, 0, 0.5);
        z-index: 1000;

    }

    .top-content {
        font-size: 50px;
        color: #5e17eb;

    }

    .popup-content {
        position: absolute;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
        background-color: white;
        padding: 20px;
        border: #5e17eb solid 1px;
        border-radius: 5px;
        box-shadow: 0px 2px 10px rgba(0, 0, 0, 0.1);
        text-align: center;
    }

    .popup-close {
        font-size: 20px;
        position: absolute;
        top: 10px;
        right: 10px;
        font-size: 20px;
        cursor: pointer;
    }

    .top-content {
        display: flex;
        justify-content: center;

    }

    .main-content p {
        font-family: 'Raleway', sans-serif;
        align-items: center;
    }
</style>
<div id="success-popup" class="popup">
    <div class="popup-content">
        <span class="popup-close" id="success-popup-close">&times;</span>
        <div class="top-content">
            <i style="color:#5e17eb;" class="fa-regular fa-circle-check"></i>
        </div>
        <div class="main-content">
            <p style="font-weight:bold; font-size:20px" id="success-message-text"></p>
        </div>
    </div>
</div>

<script>
    // Close the success popup when the close button is clicked
    $('#success-popup-close').click(function() {
        $('#success-popup').hide();
    });
</script>