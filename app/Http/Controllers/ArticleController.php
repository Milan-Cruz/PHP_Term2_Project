<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Article;
use App\Models\Topic;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class ArticleController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->authorizeResource(Article::class, 'article');
    }

    public function index(Request $request)
    {
        $sort = $request->get('sort', 'created_at');
        $articles = Article::with('user')
            ->when($sort === 'author', function ($query) {
                $query->join('users', 'users.id', '=', 'articles.created_by')
                    ->select('articles.*', 'users.name as author_name')
                    ->orderBy('author_name');
            }, function ($query) use ($sort) {
                $query->orderBy($sort);
            })
            ->paginate(10);

        $recentArticles = Article::orderBy('created_at', 'desc')->take(5)->get();

        return view('articles.index', compact('articles', 'sort', 'recentArticles'));
    }

    public function create()
    {
        $topics = Topic::all();
        return view('articles.create', compact('topics'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:20480',
            'topic_id' => 'required|exists:topics,id',
        ]);

        $imagePath = 'article_images/article_base.png'; // Caminho padrão da imagem

        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $imagePath = $image->store('article_images', 'public');

            // Redimensionar a imagem usando a biblioteca GD
            $this->resizeImage(public_path("storage/{$imagePath}"), 800, 600);
        }

        Article::create([
            'title' => $request->title,
            'content' => $request->content,
            'image' => $imagePath,
            'created_by' => Auth::id(),
            'topic_id' => $request->topic_id, // Incluindo o topic_id
        ]);

        return redirect()->route('articles.index')->with('success', 'Article created successfully.');
    }

    public function show(Article $article)
    {
        return view('articles.show', compact('article'));
    }

    public function edit(Article $article)
    {
        $topics = Topic::all();
        return view('articles.edit', compact('article', 'topics'));
    }

    public function update(Request $request, Article $article)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:20480',
            'topic_id' => 'required|exists:topics,id',
        ]);

        $imagePath = $article->image;

        if ($request->hasFile('image')) {
            // Exclui a imagem antiga se existir e não for a padrão
            if ($article->image && $article->image != 'article_images/article_base.png') {
                Storage::disk('public')->delete($article->image);
            }

            $image = $request->file('image');
            $imagePath = $image->store('article_images', 'public');

            // Redimensionar a imagem usando a biblioteca GD
            $this->resizeImage(public_path("storage/{$imagePath}"), 800, 600);
        }

        $article->update([
            'title' => $request->title,
            'content' => $request->content,
            'image' => $imagePath,
            'topic_id' => $request->topic_id, // Incluindo o topic_id
        ]);

        return redirect()->route('articles.index')->with('success', 'Article updated successfully.');
    }

    public function resetImage(Article $article)
    {
        // Exclui a imagem antiga se existir e não for a imagem padrão
        if ($article->image && $article->image != 'article_images/article_base.png') {
            Storage::disk('public')->delete($article->image);
        }

        // Atualiza o campo da imagem do artigo para a imagem padrão
        $article->update([
            'image' => 'article_images/article_base.png',
            'topic_id' => $article->topic_id, // Incluindo o topic_id para evitar erros de validação
        ]);

        return redirect()->route('articles.edit', $article)->with('success', 'Image reset successfully.');
    }

    public function destroy(Article $article)
    {
        // Exclui a imagem se existir e não for a imagem padrão
        if ($article->image && $article->image != 'article_images/article_base.png') {
            Storage::disk('public')->delete($article->image);
        }

        $article->delete();

        return redirect()->route('articles.index')->with('success', 'Article deleted successfully.');
    }

    // Função para redimensionar a imagem usando a biblioteca GD
    private function resizeImage($file, $w, $h)
    {
        list($width, $height) = getimagesize($file);
        $src = imagecreatefromstring(file_get_contents($file));
        $dst = imagecreatetruecolor($w, $h);

        imagecopyresampled($dst, $src, 0, 0, 0, 0, $w, $h, $width, $height);

        imagejpeg($dst, $file); // Salva a imagem redimensionada
    }
}
