<?php

declare(strict_types=1);

namespace App\Backend\System\Resource\Selection;

use App\Backend\System\Resource\Pilot\Pilots;
use App\Backend\System\Resource\Repository\Repositories;
use App\Shared\Http\Request;
use App\Shared\Http\Response;
use App\Shared\Http\UrlParameterContext;
use Xtompie\Collection\Collection;

class Selection
{
    public function __construct(
        protected Pilots $pilots,
        protected Repositories $repositories,
        protected Request $request,
        protected UrlParameterContext $urlParamContext,
    ) {
    }

    public function init(): void
    {
        $query = $this->request->query();
        if (isset($query['_selection']) && $query['_selection']) {
            $this->urlParamContext->set('_selection', '1');
        }
        if (isset($query['_selection_single']) && $query['_selection_single']) {
            $this->urlParamContext->set('_selection_single', '1');
        }
    }

    public function enabled(): bool
    {
        return $this->urlParamContext->has('_selection');
    }

    public function single(): bool
    {
        return $this->enabled() && $this->urlParamContext->has('_selection_single');
    }

    /**
     * @return array<string, string>
     */
    public function enable(): array
    {
        return ['_selection' => 'selection'];
    }

    /**
     * @return array<string, string>
     */
    public function enableSingle(): array
    {
        return ['_selection' => '1', '_selection_single' => '1'];
    }

    protected function clean(): void
    {
        $this->urlParamContext->remove('_selection');
    }

    /**
     * @param array<string> $ids
     */
    public function url(string $resource, array $ids): string
    {
        return $this->pilots->get($resource)->url(action: 'index', params: ['_selection' => '1', '_selection_result' => $ids]);
    }

    /**
     * @param array<string> $ids
     */
    public function result(string $resource, array $ids): Response
    {
        $entities = $this->repositories->get($resource)->findAll(['id' => $ids]);
        $keys = $this->pilots->get($resource)->selection();
        $entities = Collection::of($entities)
            ->map(fn (array $entity) => Collection::of($entity)->only($keys)->toArray())
            ->toArray()
        ;
        $result = [
            'data' => $entities,
        ];
        return Response::html('<script>window.parent.backend.modal.result(' . json_encode($result) . ')</script>');
    }

    public function cancel(): Response
    {
        return Response::html('<script>window.parent.backend.modal.cancel()</script>');
    }
}
