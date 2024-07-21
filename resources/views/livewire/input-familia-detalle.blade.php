<div class="w-full sm:w-6/12 px-4 flex flex-wrap -mx-4">
    <div class="w-full sm:w-6/12 px-4">
        <label class="block text-sm font-medium disabled:opacity-60 text-gray-700 dark:text-gray-400 invalidated:text-negative-600 dark:invalidated:text-negative-700" for="familia">Familia</label>
        <select wire:model.live="selectedFamilia" name="familia" id="familia" class="block w-full mt-1 bg-white border border-gray-300 rounded-md shadow-md focus:border-teal-500 focus:ring focus:ring-teal-200 focus:ring-opacity-50">
            <option value="">Seleccionar...</option>
            @foreach ($familias as $fam)
                <option value="{{ $fam->id }}">{{ $fam->descripcion }}</option>
            @endforeach
        </select>
    </div>
    
    <div class="w-full sm:w-6/12 px-4">
        <label class="block text-sm font-medium disabled:opacity-60 text-gray-700 dark:text-gray-400 invalidated:text-negative-600 dark:invalidated:text-negative-700" for="subfamilia">Subfamilia</label>
        <select wire:model="selectedSubfamilia" name="subfamilia" id="subfamilia" class="block w-full mt-1 bg-white border border-gray-300 rounded-md shadow-md focus:border-teal-500 focus:ring focus:ring-teal-200 focus:ring-opacity-50">
            <option value="">Seleccionar...</option>
            @foreach ($subfamilias as $sub)
                <option value="{{ $sub->id }}">{{ $sub->desripcion }}</option>
            @endforeach
        </select>
    </div>
</div>
