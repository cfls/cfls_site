

<footer class="bg-[#84DBF0]">
    <div class="w-full p-4 py-6 lg:py-8">
        <div class="md:flex md:justify-between">
            <div class="mb-6 md:mb-0 ml-7">
                <a href="/" class="flex items-center">
                    <img
                            class="object-contain w-20 sm:w-24 md:w-32 h-auto"
                            src="{{ asset('storage/' . $logo) }}"
                            alt="CFLS Logo"
                    />
                </a>
            </div>

            <div class="grid grid-cols-2 gap-8 sm:gap-6 sm:grid-cols-3 lg:grid-cols-4">

                <!-- Columna Redes Sociales -->
                <div>
                    <h2 class="mb-6 text-sm font-semibold text-gray-900 uppercase">Suivez-nous</h2>
                    <ul class="font-medium">
                        <li class="mb-4">
                            <a href="https://www.facebook.com/cfls.asbl" target="_blank" class="hover:underline">
                                Facebook
                            </a>
                        </li>
                        <li>
                            <a href="https://www.instagram.com/cflsasbl" target="_blank" class="hover:underline">
                                Instagram
                            </a>
                        </li>
                    </ul>
                </div>

                <!-- Columna Legal -->
                <div>
                    <h2 class="mb-6 text-sm font-semibold text-gray-900 uppercase">LÉGAL</h2>
                    <ul class="font-medium">
                        <li class="mb-4">
                            <a href="{{route('general-4')}}" class="hover:underline">
                                Politique de confidentialité
                            </a>
                        </li>
                    </ul>
                </div>

                <!-- Nueva Columna Información Empresa -->
                <div>
                    <h2 class="mb-6 text-sm font-semibold text-gray-900 uppercase">INFORMATIONS</h2>
                    <ul class="font-medium text-gray-600">
                        <li class="mb-2">RPM : Bruxelles</li>
                        <li class="mb-2">N° d'entreprise : BE 0422.073.229</li>
                        <li class="mb-2">N° de compte : BE38 3100 5385 3072</li>
                        <li>BIC : BBRUBEBB</li>
                    </ul>
                </div>

            </div>
        </div>
      <hr class="my-6 border-gray-200 sm:mx-auto dark:border-gray-700 lg:my-8" />
      <div class="sm:flex sm:items-center sm:justify-center">
          <span class="text-sm  sm:text-center ">© {{ date('Y') }} <a href="/" class="hover:underline">CFLS</a>. Tous droits réservés.
          </span>
          <div class="flex flex-wrap mt-4 sm:justify-start sm:mt-0 gap-4 ml-4 pt-12">
              @php
                  $soutiens = \App\Models\Soutien::where('status', 1)->get();
              @endphp
              @foreach ($soutiens as $soutien)
                  <a href="{{$soutien->url}}" target="_blank" class="text-gray-500 hover:text-gray-900 dark:hover:text-white">
                    <img src="{{ asset('storage/' . $soutien->image) }}" alt="{{ $soutien->name }}" class="w-28 h-auto mx-2">
                  </a>
              @endforeach
             {{-- <a href="https://www.facebook.com/cfls.asbl" target="_blank" class="text-gray-500 hover:text-gray-900 dark:hover:text-white">
                <svg class="w-6 h-6 text-gray-800 dark:text-white" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor" viewBox="0 0 24 24">
                    <path fill-rule="evenodd" d="M13.135 6H15V3h-1.865a4.147 4.147 0 0 0-4.142 4.142V9H7v3h2v9.938h3V12h2.021l.592-3H12V6.591A.6.6 0 0 1 12.592 6h.543Z" clip-rule="evenodd"/>
                  </svg>

                  <span class="sr-only">Facebook page</span>
              </a>
              <a href="https://www.instagram.com/cflsasbl" target="_blank" class="text-gray-500 hover:text-gray-900 dark:hover:text-white ms-5">
                <svg class="w-6 h-6 text-gray-800 dark:text-white" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24">
                    <path fill="currentColor" fill-rule="evenodd" d="M3 8a5 5 0 0 1 5-5h8a5 5 0 0 1 5 5v8a5 5 0 0 1-5 5H8a5 5 0 0 1-5-5V8Zm5-3a3 3 0 0 0-3 3v8a3 3 0 0 0 3 3h8a3 3 0 0 0 3-3V8a3 3 0 0 0-3-3H8Zm7.597 2.214a1 1 0 0 1 1-1h.01a1 1 0 1 1 0 2h-.01a1 1 0 0 1-1-1ZM12 9a3 3 0 1 0 0 6 3 3 0 0 0 0-6Zm-5 3a5 5 0 1 1 10 0 5 5 0 0 1-10 0Z" clip-rule="evenodd"/>
                  </svg>

                  <span class="sr-only">Instagram</span>
              </a>
              <a href="https://wa.me/1234567890" target="_blank" class="text-gray-500 hover:text-gray-900 dark:hover:text-white ms-5">
                  <svg class="w-6 h-6 text-gray-800 dark:text-white" fill="currentColor" viewBox="0 0 24 24" aria-hidden="true" xmlns="http://www.w3.org/2000/svg">
                      <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.472-.148-.67.149-.197.297-.767.966-.94 1.164-.173.198-.347.223-.644.075-.297-.149-1.255-.462-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.447-.52.149-.174.198-.298.298-.497.099-.198.05-.372-.025-.521-.075-.149-.669-1.611-.916-2.208-.242-.579-.487-.501-.669-.51l-.571-.01c-.198 0-.52.074-.792.372s-1.04 1.016-1.04 2.479 1.065 2.876 1.213 3.074c.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.693.625.711.226 1.36.194 1.872.118.571-.085 1.758-.719 2.006-1.412.248-.694.248-1.288.173-1.412-.074-.124-.272-.198-.57-.347z"/>
                      <path d="M12.004 2.004a9.998 9.998 0 0 0-8.708 14.721L2 22l5.42-1.29a9.998 9.998 0 1 0 4.583-18.706zm0 18.175a8.177 8.177 0 0 1-4.166-1.147l-.298-.174-3.208.764.857-3.13-.197-.323a8.176 8.176 0 1 1 7.012 3.978z"/>
                  </svg>
                  <span class="sr-only">WhatsApp</span>
              </a>--}}
          </div>
      </div>
    </div>
</footer>
