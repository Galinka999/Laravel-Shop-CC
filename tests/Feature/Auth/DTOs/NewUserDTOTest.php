<?php

declare(strict_types=1);

namespace Test\Feature\Auth\Dto;

use App\Http\Requests\SignUpFormRequest;
use Domain\Auth\DTOs\NewUserDTO;
use Tests\TestCase;

final class NewUserDTOTest extends TestCase
{
    public function test_instance_created_from_form_request(): void
    {
        $dto = NewUserDTO::fromRequest(new SignUpFormRequest([
            'name' => 'test',
            'email' => 'test@mail.ru',
            'password' => '123456'
        ]));

        $this->assertInstanceOf(NewUserDTO::class, $dto);
    }
}
