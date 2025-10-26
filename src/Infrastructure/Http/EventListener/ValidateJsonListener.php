<?php

declare(strict_types=1);

namespace Kamalo\KanbanTaskManagementSystem\Infrastructure\Http\EventListener;

use Kamalo\KanbanTaskManagementSystem\Infrastructure\Http\Attribute\ValidateJson;
use Symfony\Component\HttpKernel\Event\ControllerEvent;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

/**
 * Listener: читает атрибут ValidateJson у invokable-контроллера,
 * парсит JSON и кладёт результат в $request->attributes['json_data'].
 *
 * При ошибке выбрасывает BadRequestHttpException.
 */
class ValidateJsonListener
{
    /**
     * Undocumented function
     *
     * @param ControllerEvent $event
     * 
     * @throws BadRequestHttpException
     * @return void
     */
    public function __invoke(ControllerEvent $event): void
    {
        $callable = $event->getController();
        $controller = $this->isController($callable);

        if ($controller  === null) {
            return;
        }

        $attribute = $this->getAttribute($controller);
        if ($attribute === null) {
            return;
        }

        $request = $event->getRequest();
        $content = $request->getContent();

        if ($content === null || $content === '') {
            throw new BadRequestHttpException('Некорректный JSON.');
            return;
        }

        $data = json_decode($content, true);

        if (!is_array($data)) {
            throw new BadRequestHttpException('Некорректный JSON.');
            return;
        }

        $missing = [];
        foreach ($attribute->requiredFields as $field) {
            if (!array_key_exists($field, $data) || $data[$field] === null) {
                $missing[] = $field;
            }
        }

        if (!empty($missing)) {
            throw new BadRequestHttpException('Отсутствуют обязательные поля: ' . implode(', ', $missing));
            return;
        }

        $allowed = array_merge($attribute->requiredFields, $attribute->optionalFields);
        $unexpected = array_diff(array_keys($data), $allowed);

        if (!empty($unexpected)) {
            throw new BadRequestHttpException('Неожиданные поля: ' . implode(', ', $unexpected));
            return;
        }
       
        $request->attributes->set('json_data', $data);
    }

    private function isController(mixed $controller): ?object
    {
        if (is_object($controller)) {
            return $controller;
        }

        return null;
    }

    private function getAttribute(object $controller): ?ValidateJson
    {
        $reflection = new \ReflectionClass($controller);

        $attributes = $reflection->getAttributes(ValidateJson::class);

        if (empty($attributes)) {
            return null;
        }

        /** @var ValidateJson $attribute */
        return $attributes[0]->newInstance();
    }
}
