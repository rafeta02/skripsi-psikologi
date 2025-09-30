<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Traits\CsvImportTrait;
use App\Http\Requests\MassDestroyArticleTagRequest;
use App\Http\Requests\StoreArticleTagRequest;
use App\Http\Requests\UpdateArticleTagRequest;
use App\Models\ArticleTag;
use Gate;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Yajra\DataTables\Facades\DataTables;

class ArticleTagController extends Controller
{
    use CsvImportTrait;

    public function index(Request $request)
    {
        abort_if(Gate::denies('article_tag_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        if ($request->ajax()) {
            $query = ArticleTag::query()->select(sprintf('%s.*', (new ArticleTag)->table));
            $table = Datatables::of($query);

            $table->addColumn('placeholder', '&nbsp;');
            $table->addColumn('actions', '&nbsp;');

            $table->editColumn('actions', function ($row) {
                $viewGate      = 'article_tag_show';
                $editGate      = 'article_tag_edit';
                $deleteGate    = 'article_tag_delete';
                $crudRoutePart = 'article-tags';

                return view('partials.datatablesActions', compact(
                    'viewGate',
                    'editGate',
                    'deleteGate',
                    'crudRoutePart',
                    'row'
                ));
            });

            $table->editColumn('name', function ($row) {
                return $row->name ? $row->name : '';
            });
            $table->editColumn('slug', function ($row) {
                return $row->slug ? $row->slug : '';
            });

            $table->rawColumns(['actions', 'placeholder']);

            return $table->make(true);
        }

        return view('admin.articleTags.index');
    }

    public function create()
    {
        abort_if(Gate::denies('article_tag_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return view('admin.articleTags.create');
    }

    public function store(StoreArticleTagRequest $request)
    {
        $articleTag = ArticleTag::create($request->all());

        return redirect()->route('admin.article-tags.index');
    }

    public function edit(ArticleTag $articleTag)
    {
        abort_if(Gate::denies('article_tag_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return view('admin.articleTags.edit', compact('articleTag'));
    }

    public function update(UpdateArticleTagRequest $request, ArticleTag $articleTag)
    {
        $articleTag->update($request->all());

        return redirect()->route('admin.article-tags.index');
    }

    public function show(ArticleTag $articleTag)
    {
        abort_if(Gate::denies('article_tag_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return view('admin.articleTags.show', compact('articleTag'));
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
