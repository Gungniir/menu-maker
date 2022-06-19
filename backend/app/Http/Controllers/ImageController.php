<?php

namespace App\Http\Controllers;

use App\Models\Image;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\StreamedResponse;

class ImageController extends Controller
{
    public function __construct() {
        $this->authorizeResource(Image::class);
    }

    /**
     * Display a listing of the resource.
     *
     * @return JsonResponse
     */
    public function index(): JsonResponse
    {
        $pagination = Image::whereCreatorId(Auth::id())->paginate(9);

        return response()->json($pagination);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function store(Request $request): JsonResponse
    {
        $input = $request->validate([
            'name' => 'required|string|max:255',
            'file' => 'required|dimensions:min_width=200,min_height=200|mimes:jpg,tiff,png,webp'
        ]);

        /** @noinspection NullPointerExceptionInspection */
        $path = $request->file('file')->store('images/' . Auth::id(), 'public');

        $image = Image::create([
            'creator_id' => Auth::id(),
            'name' => $input['name'],
            'filename' => $path,
        ]);

        return response()->json($image);
    }

    /**
     * Display the specified resource.
     *
     * @param Image $image
     * @return StreamedResponse
     */
    public function show(Image $image): string
    {
        return Storage::temporaryUrl('images/' . Auth::id() . '/' . $image->filename, Carbon::now()->addHour());
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Image $image
     * @return JsonResponse
     */
    public function destroy(Image $image): JsonResponse
    {
        $image->delete();

        return response()->json($image);
    }
}
