<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Traits\CsvImportTrait;
use App\Http\Requests\MassDestroyArticleTagRequest;
use App\Http\Requests\StoreArticleTagRequest;
use App\Http\Requests\UpdateArticleTagRequest;
use App\Models\ArticleTag;
use Gate;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ArticleTagController extends Controller
{
    use CsvImportTrait;

    public function index()
    {
        abort_if(Gate::denies('article_tag_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $articleTags = ArticleTag::all();

        return view('frontend.articleTags.index', compact('articleTags'));
    }

    public function create()
    {
        abort_if(Gate::denies('article_tag_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return view('frontend.articleTags.create');
    }

    public function store(StoreArticleTagRequest $request)
    {
        $articleTag = ArticleTag::create($request->all());

        return redirect()->route('frontend.article-tags.index');
    }

    public function edit(ArticleTag $articleTag)
    {
        abort_if(Gate::denies('article_tag_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return view('frontend.articleTags.edit', compact('articleTag'));
    }

    public function update(UpdateArticleTagRequest $request, ArticleTag $articleTag)
    {
        $articleTag->update($request->all());

        return redirect()->route('frontend.article-tags.index');
    }

    public function show(ArticleTag $articleTag)
    {
        abort_if(Gate::denies('article_tag_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return view('frontend.articleTags.show', compact('articleTag'));
    }

    public function destroy(ArticleTag $articleTag)
    {
        abort_if(Gate::denies('article_tag_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $articleTag->delete();

        return back();
    }

    public function massDestroy(MassDestroyArticleTagRequest $request)
    {
        $articleTags = ArticleTag::find(request('ids'));

        foreach ($articleTags as $articleTag) {
            $articleTag->delete();
        }

        return response(null, Response::HTTP_NO_CONTENT);
    }
}
