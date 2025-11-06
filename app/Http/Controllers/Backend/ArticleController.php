<?php

namespace App\Http\Controllers\Backend;

use App\Enums\ActiveStatus;
use App\Http\Controllers\Controller;
use App\Http\Requests\Article\StoreRequest;
use App\Http\Requests\Article\UpdateRequest;
use App\Models\Article;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class ArticleController extends Controller
{
    public function index()
    {
        $articles = Article::orderBy('created_at', 'desc')->paginate(10);

        return view('backend.article.index', compact('articles'));
    }

    public function create()
    {
        return view('backend.article.create');
    }

    public function store(StoreRequest $request)
    {
        try {
            DB::beginTransaction();

            if (!empty($request->file('image'))) {
                $checkExtensionImage = checkExtensionImage($request->file('image'));
                if (!$checkExtensionImage) {
                    return redirect()->back()->withInput()->with('error', __('Only PNG, JPEG and JPG files can be uploaded.'));
                }
            }

            $data = $request->all();

            if ($request->hasFile('image')) {     // image
                $imageUpload = $request->file('image');
                //get name file
                $name = $imageUpload->getClientOriginalName();
                //random name file for save database
                $nameImage = Str::random(3) . "_" . $name;
                // save file
                $imageUpload->move(public_path('/upload/article/'), $nameImage);
                $data['image'] = '/upload/article/' . $nameImage;
            }

            $handleData = $this->prepareArticle($data);
            Article::create($handleData);

            DB::commit();
            return redirect()->route('admin.article.index')->with('success', 'Đã thêm 1 Article!');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::debug($e);
            return redirect()->back()->withInput()->with('error', 'Thêm Article không thành công');
        }
    }

    public function edit($id)
    {
        $article = Article::where('id', $id)->first();
        if (!$article) {
            return redirect()->route('admin.article.index')->with('error', __('The requested resource is not available'));
        }

        return view('backend.article.edit', compact('article'));
    }

    public function update(UpdateRequest $request, $id)
    {
        try {
            DB::beginTransaction();

            $article = Article::where('id', $id)->first();
            if (!$article) {
                return redirect()->route('admin.article.index')->with('error', __('The requested resource is not available'));
            }

            if (!empty($request->file('image'))) {
                $checkExtensionImage = checkExtensionImage($request->file('image'));
                if (!$checkExtensionImage) {
                    return redirect()->back()->withInput()->with('error', __('Only PNG, JPEG and JPG files can be uploaded.'));
                }
            }

            $data = $request->all();
            if ($request->hasFile('image')) {     // image
                $imageUpload = $request->file('image');
                //get name file
                $name = $imageUpload->getClientOriginalName();
                //random name file for save database
                $nameImage = Str::random(3) . "_" . $name;
                // save file
                $imageUpload->move(public_path('/upload/article/'), $nameImage);
                $data['image'] = '/upload/article/' . $nameImage;
            } else {
                $data['image'] = $article->image;
            }

            $handleData = $this->prepareArticle($data);
            $article->update($handleData);

            DB::commit();
            return redirect()->route('admin.article.index')->with('success', 'Đã sửa thành công bài viết mang ID số ' . $article->id . '!');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::debug($e);
            return redirect()->back()->with('error', 'Sửa không thành công bài viết mang ID số ' . $article->id . '!');
        }
    }

    public function handle(Request $request, $action, $id)
    {
        $article = Article::where('id', $id)->first();
        if (!$article) {
            return redirect()->route('admin.article.index')->with('error', __('The requested resource is not available'));
        }
        try {
            switch ($action) {
                case 'delete':
                    $article->delete();
                    $request->session()->flash('success', 'Đã xóa thành công bài viết mang ID số ' . $id . '!');
                    break;
                case 'status':
                    $article->publish = $article->publish == ActiveStatus::ACTIVE ? ActiveStatus::INACTIVE : ActiveStatus::ACTIVE;
                    $article->save();
                    break;
                default:
                    dd("Lỗi r");
                    break;
            }
            return redirect()->route('admin.article.index');
        } catch (\Exception $exception) {
            Log::debug($exception->getMessage());
        }
    }

    private function prepareArticle(array $data)
    {
        $article = [
            'name' => $data['name'] ?? '',
            'slug' => Str::slug($data['name'] ?? ''),
            'description' => $data['description'] ?? '',
            'content' => $data['content'] ?? '',
            'image' => $data['image'] ?? '',
            'author_id' => Auth::user()->id ?? '',
            'publish' => $data['publish'] ?? ''
        ];

        return $article;
    }
}
