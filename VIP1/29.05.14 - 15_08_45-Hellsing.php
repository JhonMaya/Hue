<?php exit() ?>--by Hellsing 91.97.241.79
local range = player.range + GetDistance(player.minBBox)
local colors = {
	{ current = 255, step = 1, min = 0, max = 255, mode = -1 },
	{ current = 255, step = 2, min = 0, max = 255, mode = -1 },
	{ current = 255, step = 3, min = 0, max = 255, mode = -1 },
}

function mixColors()

	for _, color in ipairs(colors) do
		color.current = color.current + color.mode * color.step
		if color.current < color.min then
			color.current = color.min
			color.mode = 1
		elseif color.current > color.max then
			color.current = color.max
			color.mode = -1
		end
	end

end

function OnDraw()

	mixColors()
	DrawCircle3D(player.x, player.y, player.z, range, 2, ARGB(255, colors[1].current, colors[2].current, colors[3].current))

end