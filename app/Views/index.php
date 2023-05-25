<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Azure OCR Tester</title>
    <meta name="description" content="The small framework with powerful features">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="shortcut icon" type="image/png" href="/favicon.ico">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
    <!-- <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous"> -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.0.1/css/font-awesome.min.css"  />
    <link href = "<?= base_url()?>/assets/css/jquery.dm-uploader.min.css" rel="stylesheet">
    <!-- STYLES -->
    <style>
        .footer {
            position: absolute;
            bottom: 0;
            width: 100%;
            height: 60px;
            background-color: #f5f5f5;
        }
        .drop-container {
            position: relative;
            display: flex;
            gap: 10px;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            height: 200px;
            padding: 20px;
            border-radius: 10px;
            border: 2px dashed #555;
            color: #444;
            cursor: pointer;
            transition: background .2s ease-in-out, border .2s ease-in-out;
            }

            .drop-container:hover {
            background: #eee;
            border-color: #111;
            }

            .drop-container:hover .drop-title {
            color: #222;
            }

            .drop-title {
            color: #444;
            font-size: 20px;
            font-weight: bold;
            text-align: center;
            transition: color .2s ease-in-out;
            }

            .result-area{
                width:100%;
                height:100%;
                border: 1px solid #ccc;
                border-radius:4px;
                text-align:center;
            }

            .result-area:active {
                border-color:red
            }
            
            .d-flex{
                display:flex;
            }
            .justify-content-end{
                justify-content:flex-end;
            }

            .display-none{
                display:none;
            }

            .display-block{
                display:block;
            }

            .mt-15{
                margin-top:15px;
            }
            .mb-15{
                margin-bottom:15px;
            }

            .text-green{
                color:green;
            }

            .url{
                color:green;
                margin-bottom:15px;
                margin-top:15px;
                text-align:center;
            }

    </style>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.4/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>
    <!-- <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script> -->
</head>
<body>
    <div class="container">
        <h1 >Azure OCR Tester  1.0</h1>
        <ul class="nav nav-tabs" style="margin-top:25px">
            <li id="memu-url" class="active"><a data-toggle="tab" href="#url">With URL</a></li>
            <li id="memu-file"><a data-toggle="tab" href="#file">With File</a></li>
        </ul>
        <div class="tab-content" style="margin-top:25px">
            <div id="url" class="tab-pane fade in active">
                <div class="form-group">
                    <label for="url-area" style="font-size:18px">URL:</label>
                    <input type="url" class="form-control" id="url-area">
                </div>
            </div>
            <div id="file" class="tab-pane fade">
                <div id="drag-and-drop-zone">
                    <label style="font-size:18px"  for="images" class="drop-container">
                        <span class="drop-title">Drop files here or Select Files</span>
                        <h3 id="file-name"></h3>
                        <input style="display:none" type="file" id="images" accept=".jpg,.jpeg,.png,.pdf,.tiff" required>
                    </label>    
                </div>
                <!-- <label style="font-size:18px;margin-top:25px">File List</label>
                <ul class="list-group" id="file-list" style="margin-top:25px">
                    <h5>No File Selected</h5>
                </ul> -->
            </div>
        </div>
        <div class="d-flex justify-content-end">
            <button class="btn btn-primary btn-lg d-block " style="margin-top:25px" id="run" type="submit">Run OCR</button>
        </div>
        
        <div class="form-group display-none"  id="result-div" style="margin-top:25px">
            <label for="result-area" style="font-size:18px;margin-bottom:15px">Result</label>
            <div class="d-flex justify-content-end">
                <button id="copy-btn">
                    <i class="fa fa-files-o" style="font-size:18px" aria-hidden="true"></i>
                </button>
            </div>
            
            <div id="result-area" >
            </div>
        </div>
        
    </div>

    <script src="<?= base_url() ?>/assets/js/jquery.dm-uploader.min.js"></script>

    <script>
        var file;
        var mode=0
        function add_file(id,data){
            // console.log(files.length)
            // files.push(file);
            // let file_list=""
            // for(let i=0;i<files.length;i++){
            //     file_list+='<li class="list-group-item">'+files[i].name+'</li>'
            // }
            file=data;
            console.log(file)
            $('#file-name').text(file.name);
            // $('#file-list').html(file_list);
        }



        $(document).ready(function() {
            $('#drag-and-drop-zone').dmUploader({
                auto: false,
                maxFileSize : 30000000,
                extFilter : ["jpg" , "jpeg" , "png" , "pdf"],
                onNewFile: function(id, file){
                // When a new file is added using the file selector or the DnD area
                // console.log(file);
                add_file(id, file);
                },
            });

            $('#memu-url').on('click',function (){
                mode=0
            })

            $('#memu-file').on('click',function (){
                mode=1
            })

            $('#run').on('click',function(){
                get_values()
            })

            $('#copy-btn').on('click',function (){
                let text=$('#result-area').html()
                copyText(text)
            })
        });

        function copyText(text){
            let result=text;
            while(1){
                let temp=result.replace('<br>','\u000A');
                if(temp==result){
                    break;
                }else{
                    result=temp
                }
            }
            navigator.clipboard.writeText(result);
        }

        function get_values(){
            let formdata = new FormData($('#form')[0]);
            if(mode==1){
                if(file_validation()){
                    formdata.append('mode',mode)
                    formdata.append('file',file);
                    send_request(formdata)
                } 
                else{
                    alert('Please Select file')
                    return
                }
                    
            }else{
                let url= document.getElementById("url-area").value
                if(url_validation(url)){
                    formdata.append('mode',mode)
                    formdata.append('url',url);
                    send_request(formdata)
                }else{
                    alert('Please correct Image URL')
                    return
                }
            }
        }

        function send_request(formdata){
            $.ajax({
                url: '<?= base_url() ?>/runOCR',
                type: 'POST',
                cache:false,
                contentType: false,
                processData: false,
                data: formdata,
                dataType: 'json',
                beforeSend: function() {        
                    $('#run').prop('disabled' , true);
                    $('#run').text('Processing..');
                    $('#result-div').removeClass('display-block').addClass('display-none');
                },
            
            }).done(function(res){
                console.log("res",res)
                $('#run').prop('disabled' , false);
                $('#run').text('Submit');
                if(res.success){    
                    $('#result-div').removeClass('display-none').addClass('display-block');
                    $('#result-area').html(res.result)
                }else{
                
                }
            
            });
        }

        function url_validation(url){
            const pattern=/[(http(s)?):\/\/(www\.)?a-zA-Z0-9@:%._\+~#=]{2,256}\.[a-z]{2,6}\b([-a-zA-Z0-9@:%_\+.~#?&//=]*)/
            return pattern.test(url)
        }
        
        function file_validation(){
            if(file==null)
                return false;
            else
                return true;
        }

        function makeDisplayData(urls,texts){
            let result=""
            for(let i=0;urls.length;i++){
                result+='<br><h3 class="url">'+urls[i] +'</h3><br>'+texts[i];
            }
            return result;
        }

    </script>

<!-- -->

</body>
</html>
