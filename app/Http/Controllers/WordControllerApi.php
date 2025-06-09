<?php



namespace App\Http\Controllers;

use App\Models\Player;
use App\Models\Word;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use App\Helpers\WordHelper;

/**
 * @OA\Info(
 *     version="1.0.0",
 *     title="Word Guess Game API",
 *     description="API documentation for the Word Guess Game"
 * )
 */

class WordControllerApi extends Controller
{
    /**
     * @OA\Post(
     *     path="/api/words/check",
     *     summary="Submit a guess for the word game",
     *     tags={"Game"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"guess","token"},
     *             @OA\Property(property="guess", type="string", example="apple"),
     *             @OA\Property(property="token", type="string", example="123e4567-e89b-12d3-a456-426614174000")
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Guess accepted",
     *         @OA\JsonContent(
     *             @OA\Property(property="result", type="array", @OA\Items(
     *                 @OA\Property(property="letter", type="string"),
     *                 @OA\Property(property="color", type="string"),
     *                 @OA\Property(property="order", type="boolean"),
     *                 @OA\Property(property="exist", type="boolean")
     *             )),
     *             @OA\Property(property="message", type="string"),
     *             @OA\Property(property="tries_left", type="integer")
     *         )
     *     ),
     *     @OA\Response(
     *         response=403,
     *         description="Game Over"
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Validation error"
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="No word scheduled for today"
     *     )
     * )
     */
    public function check(Request $request)
    {
        try {
            $request->validate([
                'guess' => 'required|string|size:5',
                'token' => 'required|string', // Require player token
            ]);
        } catch (ValidationException $e) {
            return response()->json(['errors' => $e->errors()], 422);
        }

        // Check if guess is a valid English word
        if (!WordHelper::isEnglishWord($request->guess)) {
            return response()->json(['errors' => ['guess' => ['Guess is not a valid English 5-letter word.']]], 422);
        }

        $guess = strtoupper($request->guess);
        $today = now()->toDateString();

        // Find or create player for today
        $player = Player::firstOrCreate(
            ['token' => $request->token, 'date' => $today]
        );

        // Find next available try slot
        for ($i = 1; $i <= 6; $i++) {
            $tryField = "try$i";
            if (empty($player->$tryField)) {
                $player->$tryField = $guess;
                $player->save();
                break;
            }
        }

        // Count used tries
        $usedTries = 0;
        for ($i = 1; $i <= 6; $i++) {
            if (!empty($player->{"try$i"})) $usedTries++;
        }

        if ($usedTries > 6) {
            return response()->json(['message' => 'Game Over. You have used all 6 tries.'], 403);
        }

        $wordOfDay = Word::where('schedule_at', $today)->first();
        if (!$wordOfDay) {
            return response()->json(['error' => 'No word scheduled for today.'], 404);
        }

        $correct = strtoupper($wordOfDay->word);
        $result = [];
        $allGreen = true;

        for ($i = 0; $i < 5; $i++) {
            if ($guess[$i] === $correct[$i]) {
                $result[$i] = [
                    'letter' => $guess[$i],
                    'color' => 'green',
                    'order' => true,
                    'exist' => true,
                ];
            } else {
                $allGreen = false;
                if (strpos($correct, $guess[$i]) !== false) {
                    $result[$i] = [
                        'letter' => $guess[$i],
                        'color' => 'yellow',
                        'order' => false,
                        'exist' => true,

                    ];
                } else {
                    $result[$i] = [
                        'letter' => $guess[$i],
                        'color' => 'black',
                        'order' => false,
                        'exist' => false,

                    ];
                }
            }
        }

        if ($allGreen) {
            return response()->json(['result' => $result, 'message' => 'Well done!']);
        }

        if ($usedTries >= 6) {
            return response()->json(['result' => $result, 'message' => 'Game Over. You have used all 6 tries.']);
        }

        return response()->json(['result' => $result, 'tries_left' => 6 - $usedTries]);
    }
}
