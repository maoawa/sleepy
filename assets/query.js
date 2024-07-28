document.addEventListener("DOMContentLoaded", () => {
    fetch('query/')
        .then(response => response.text())
        .then(data => {
            const statusElement = document.getElementById('status');
            const additionalInfoElement = document.getElementById('additional-info');
            if (data.trim() === '0') {
                statusElement.textContent = '睡似了';
                statusElement.classList.add('sleeping');
                additionalInfoElement.textContent = '如果情况紧急，请直接以电话等方式和毛毛取得联系。';
            } else if (data.trim() === '1') {
                statusElement.textContent = '醒着';
                statusElement.classList.add('awake');
                additionalInfoElement.textContent = '这意味着你可以直接通过任何方式和毛毛取得联系。';
            } else {
                statusElement.textContent = '<!>后端响应出错<!>';
                statusElement.classList.add('error');
                additionalInfoElement.textContent = '错误会很快恢复。如果情况紧急，请直接以电话等方式和毛毛取得联系。';
            }
        })
        .catch(error => {
            console.error('Error:', error);
            const statusElement = document.getElementById('status');
            const additionalInfoElement = document.getElementById('additional-info');
            statusElement.textContent = '<!>请求失败<!>';
            statusElement.classList.add('error');
            additionalInfoElement.textContent = '错误会很快恢复。如果情况紧急，请直接以电话等方式和毛毛取得联系。';
        });
});