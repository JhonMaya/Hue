<?php exit() ?>--by Mirage 70.77.113.157

HK1 = 32 -- Z

local wp = WayPointManager()

function OnWndMsg(msg, keycode )
	if keycode == HK1 and msg == KEY_DOWN then
  for i = 1, heroManager.iCount do
   local hero = heroManager:getHero(i)

   if wp:GetWayPointChangeRate(hero, 1) > 10 then
    print(hero.charName .. ' Highly likely uses BoL.')
   end
  end
 end
end