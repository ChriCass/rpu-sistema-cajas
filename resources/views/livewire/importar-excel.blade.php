<div>
    <div x-data="{ fileName: '' }" class="flex flex-col items-center justify-center w-full">
        <label for="file-upload" class="flex flex-col items-center justify-center w-full h-32 border-2 border-dashed border-gray-400 rounded-lg cursor-pointer hover:border-gray-600">
            <div class="flex flex-col items-center justify-center pt-5 pb-6" x-show="!fileName">
                <svg class="w-10 h-10 mb-3 text-green-500" fill="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                    <path d="M9 2C7.895 2 7 2.895 7 4V5H5C4.447 5 4 5.447 4 6V18C4 18.553 4.447 19 5 19H9.235L10 20.765L10.765 19H19C19.553 19 20 18.553 20 18V8L15 3H9ZM14 10V7.5L17.5 11H15C14.447 11 14 10.553 14 10ZM9 7H11V9H9V7ZM13 7V9H11V7H13ZM9 10H11V12H9V10ZM13 10H11V12H13V10ZM9 13H11V15H9V13ZM13 13H11V15H13V13ZM15 13V12H17.293L14 8.707V10C14 10.553 14.447 11 15 11H17L15 13Z" />
                </svg>
                <p class="mb-2 text-sm text-gray-500 dark:text-gray-400">
                    <span class="font-semibold">Click para subir un archivo Excel</span> o arrastra y suelta
                </p>
                <p class="text-xs text-gray-500 dark:text-gray-400">XLS, XLSX hasta 10MB</p>
            </div>
            
            <div x-show="fileName" class="flex flex-col items-center justify-center pt-5 pb-6">
                <svg class="w-10 h-10 mb-3 text-green-500" fill="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                    <path d="M9 2C7.895 2 7 2.895 7 4V5H5C4.447 5 4 5.447 4 6V18C4 18.553 4.447 19 5 19H9.235L10 20.765L10.765 19H19C19.553 19 20 18.553 20 18V8L15 3H9ZM14 10V7.5L17.5 11H15C14.447 11 14 10.553 14 10ZM9 7H11V9H9V7ZM13 7V9H11V7H13ZM9 10H11V12H9V10ZM13 10H11V12H13V10ZM9 13H11V15H9V13ZM13 13H11V15H13V13ZM15 13V12H17.293L14 8.707V10C14 10.553 14.447 11 15 11H17L15 13Z" />
                </svg>
                <p class="text-sm text-gray-500 dark:text-gray-400" x-text="fileName"></p>
            </div>
    
            <input id="file-upload" type="file" accept=".xls,.xlsx" class="hidden" @change="fileChosen">
        </label>

        <div class="flex justify-center mt-5">
            <x-button teal label="Procesar" />
        </div>
    </div>
</div>

<script>
    function fileChosen(event) {
        const input = event.target;
        if (input.files && input.files[0]) {
            this.fileName = input.files[0].name;
        }
    }
</script>