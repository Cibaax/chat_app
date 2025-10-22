<template>
  <div class="min-h-screen bg-gradient-to-br from-blue-50 to-indigo-100">
    <div class="container mx-auto px-4 py-8">
      <div class="max-w-2xl mx-auto">
        <!-- Tarjeta de prueba con clases Tailwind -->
        <div class="bg-white rounded-lg shadow-lg p-6 border border-blue-200">
          <h1 class="text-3xl font-bold text-blue-600 mb-4">
            🎉 ¡Tailwind está funcionando!
          </h1>
          <p class="text-gray-600 mb-4">
            Si ves esta tarjeta con colores y sombras, Tailwind CSS está configurado correctamente.
          </p>
          <div class="flex gap-2">
            <button class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded-lg transition-colors">
              Botón Primario
            </button>
            <button class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-lg transition-colors">
              Botón Secundario
            </button>
          </div>
        </div>
        
        <!-- Grid de prueba -->
        <div class="mt-8 grid grid-cols-1 md:grid-cols-3 gap-4">
          <div class="bg-red-100 p-4 rounded-lg text-red-800">Columna 1</div>
          <div class="bg-green-100 p-4 rounded-lg text-green-800">Columna 2</div>
          <div class="bg-purple-100 p-4 rounded-lg text-purple-800">Columna 3</div>
        </div>
      </div>
    </div>
  </div>
</template>