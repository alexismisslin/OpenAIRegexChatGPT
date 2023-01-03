<?php

namespace  App\Service;

use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Orhanerday\OpenAi\OpenAi;


/**
 * Ce service gère la partie API de OpenAI. C'est ici que l'on va faire nos appels à chat gpt pour générer une histoire.
 */
class OpenAIService{

    public function __construct(private ParameterBagInterface $parameterBag){

    }

    /**
     * @param string $regex
     * @return string
     * @throws \Exception
     */
    public function getHistory(string $regex): string{
        $openai_api_key = $this->parameterBag->get('OPENAI_API_KEY');
        $open_ai = new OpenAi($openai_api_key);
        $complete = $open_ai->completion([
            'model' => 'text-davinci-003',
            'prompt' => 'Explique en français et sous forme d\'histoire la REGEX suivante : '.$regex,
            'temperature' => 0,
            'max_tokens' => 3500,
            'frequency_penalty' => 0.5,
            'presence_penalty' => 0,
        ]);

        $json = json_decode($complete, true);

        if(isset($json['choices'][0]['text'])){ //si le tableau choices, le tableau 0 et la clef text existent alors..
            return $json['choices'][0]['text'];
        }

        return $json = 'Une erreur est survenue. Père Castor n\'est pas très en forme en ce moment...';
    }
}