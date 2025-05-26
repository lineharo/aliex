<?php

namespace App\Http\Controllers\Admin;

use App\Models\Article;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;


class ArticleController extends Controller
{
    public function index()
    {
        $articles = Article::latest()->paginate(50);
        return view('areas.admin.articles.index', compact('articles'));
    }


    public function create()
    {
        return view('areas.admin.articles.create');
    }

    public function store(Request $request)
    {
        // Валидация данных
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'slug' => 'required|string|unique:articles,slug|max:255',
            'seo_title' => 'nullable|string|max:255',
            'seo_description' => 'nullable|string|max:255',
            'seo_keywords' => 'nullable|string|max:255',
            'preview' => 'nullable|string',
            'content' => 'nullable|string',
            'published_at' => 'nullable|date',
            'views' => 'nullable|integer|min:0',
            'published' => 'nullable|boolean',
            'image_preview' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        // Создание статьи
        $article = new Article();
        $article->name = $validatedData['name'];
        $article->slug = $validatedData['slug'];
        $article->seo_title = $validatedData['seo_title'] ?? null;
        $article->seo_description = $validatedData['seo_description'] ?? null;
        $article->seo_keywords = $validatedData['seo_keywords'] ?? null;
        $article->preview = $validatedData['preview'] ?? null;
        $article->content = $validatedData['content'] ?? null;
        $article->published_at = $validatedData['published_at'] ?? null;
        $article->views = $validatedData['views'] ?? 0;
        $article->published = $validatedData['published'] ?? 0;

        // Обработка изображения, если загружено
        if ($request->hasFile('image_preview')) {
            $article->save(); // Сначала сохраняем статью, чтобы получить ID
            $imagePath = $request->file('image_preview')->storeAs(
                'images/articles/' . $article->id,
                $request->file('image_preview')->getClientOriginalName(),
                'public'
            );
            $article->image_preview = $imagePath;
        }

        // Сохранение статьи
        $article->save();

        // Перенаправление с сообщением об успехе
        return redirect()->route('admin.articles.index')->with('success', 'Статья успешно создана!');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($articleId)
    {
        $article = Article::where('id', $articleId)->firstOrFail();
        return view('areas.admin.articles.edit', compact('article'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Article $article)
    {
        // Валидация данных
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'slug' => 'required|string|unique:articles,slug,' . $article->id . '|max:255',
            'seo_title' => 'nullable|string|max:255',
            'seo_description' => 'nullable|string|max:255',
            'seo_keywords' => 'nullable|string|max:255',
            'preview' => 'nullable|string',
            'content' => 'nullable|string',
            'published_at' => 'nullable|date',
            'views' => 'nullable|integer|min:0',
            'published' => 'nullable|boolean',
            'image_preview' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        // Обработка изображения, если загружено
        if ($request->hasFile('image_preview')) {
            $imagePath = $request->file('image_preview')->storeAs(
                'images/articles/' . $article->id,
                $request->file('image_preview')->getClientOriginalName(),
                'public'
            );
            $article->image_preview = $imagePath;
        }

        // Обновление статьи
        $article->name = $validatedData['name'];
        $article->slug = $validatedData['slug'];
        $article->seo_title = $validatedData['seo_title'] ?? null;
        $article->seo_description = $validatedData['seo_description'] ?? null;
        $article->seo_keywords = $validatedData['seo_keywords'] ?? null;
        $article->preview = $validatedData['preview'] ?? null;
        $article->content = $validatedData['content'] ?? null;
        $article->published_at = $validatedData['published_at'] ?? null;
        $article->views = $validatedData['views'] ?? 0;
        $article->published = $validatedData['published'] ?? 0;

        // Сохранение статьи
        $article->save();

        // Перенаправление с сообщением об успехе
        return redirect()->route('admin.articles.index')->with('success', 'Статья успешно обновлена!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Article $article)
    {
        //
    }
}
