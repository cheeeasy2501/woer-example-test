<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
    <link href="app/Modules/RestoreDb/Assets/css/main.css" ref="stylesheet">
</head>
<body>
    <h1>Модуль</h1>
    <div class="ex-1 group">
        <h2>Ex-1</h2>
        <div class="input-group">
            <button id="restore">Восстановить базу данных</button>
        </div>
        <div class="input-group">
            <h3>Результат</h3>
            <div id="ex-1-result"></div>
        </div>
    </div>
    <div class="ex-2 group">
        <h2>Ex-2</h2>
        <div class="input-group">
            <div class="input-name">Page</div>
            <input type="number" min="0" id="page" class="input-value">
        </div>
        <div class="input-group">
            <div class="input-name">Limit</div>
            <input type="number" min="0" id="limit" class="input-value">
        </div>
        <div class="input-group">
            <button id="getData">Запросить данные</button>
        </div>
        <div class="input-group">
            <h3>Результат</h3>
            <div id="ex-2-result"></div>
        </div>
    </div>
    <div class="ex-4 group">
        <h2>Ex-4</h2>
        <div id="body-4"></div>
        <button id="getFilterData">Запросить данные</button>
        <div class="input-group result-4">
            <h3>Результат</h3>
            <div id="ex-4-result"></div>
        </div>
    </div>

 <script>
     async function getData() {
         const result = document.querySelector("#ex-2-result");
         try {
             document.querySelector("#getData").setAttribute("disabled", "disabled");
             const page = document.querySelector("#page").value;
             const limit = document.querySelector("#limit").value;

             if(!checkValue(page, limit)) {
                 result.innerHTML = "Пустые значения!";
                 return ;
             }

             const res = await fetch(window.location.href + `/get_table_data/page=${page}&limit=${limit}`);
             const data = await res.json();
             result.innerHTML = JSON.stringify(data);
         } catch(e) {
             result.innerHTML = JSON.stringify(e);
         } finally {
             document.querySelector("#getData").removeAttribute("disabled");
         }
     }

    async function restoreAll() {
         const result = document.querySelector("#ex-1-result");
         try {
             document.querySelector("#restore").setAttribute("disabled", "disabled");
             const res = await fetch(window.location.href + "/restore", { method:"POST" });
             const status = res.status;
             const data = await res.json();

             if(status === 200) {
                 result.innerHTML = JSON.stringify(data);
                 return;
             }
         } catch (e) {
             result.innerHTML = JSON.stringify(e);
         } finally {
             document.querySelector("#restore").removeAttribute("disabled");
         }
     }

    async function getHeadNames() {
        const headers = await fetch(window.location.href + "/headers", { method:"GET" }).then(response => response.json());
        return headers;
     }

     async function addHeadersInDom() {
        const body = document.querySelector('#body-4');
        const headers = await this.getHeadNames();
        headers.forEach(header => {
            const headElement = document.createElement('div');
            const headInput = document.createElement('input');
            headElement.className = "element";
            headElement.innerHTML = `<strong class="input-name">${header}</strong>`;
            headInput.setAttribute('name', header);
            headInput.setAttribute('class', 'filter-input');
            headElement.append(headInput);
            body.append(headElement);
         })
     }

    async function getFilterData() {
        const data = [];
        const elements = document.querySelectorAll(".element");
        elements.forEach(element => {
            const input = element.lastChild;
            if(input.value.trim().length > 1) {
                data.push({field: input.getAttribute("name"), value: input.value});
            }
        })
        const filteredData = await fetch(window.location.href + "/filter",
            { method:"POST", body: JSON.stringify(data),
                headers: {
                "Content-Type": "application/json"
            } }).then(response => response.json());
        const result = document.querySelector("#ex-4-result")
        result.innerHTML = filteredData;
     }

    function checkValue(...values) {
        checked = true;
        values.forEach((element) => {
            if(element.length < 1) {
                checked = false;
            }
        });

        return checked;
     }

     document.querySelector("#restore").onclick = restoreAll;
     document.querySelector("#getData").onclick = getData;
     document.querySelector("#getFilterData").onclick = getFilterData;
     addHeadersInDom();
 </script>
 <style>
    .group {
        padding: 8px;
        border: 1px solid black;
    }
    .input-group {
        margin-bottom: 8px;
    }
    #body-4 {
        display: flex;
        flex-wrap: wrap;
    }
    .element {
        display: flex;
        flex-direction: column;
        margin:0 8px 16px 0;
    }
    .input-name {
        margin-bottom: 8px;
    }
 </style>
</body>
</html>
