<?php exit() ?>--by pqmailer 141.101.98.216
if not VIP_USER or myHero.charName ~= "TwistedFate" then return end
local Skills, Keys, Items, Data, Jungle, Helper, MyHero, Minions, Crosshair, Orbwalker = AutoCarry.Helper:GetClasses()
local Cards = {Red = false, Gold = false, Blue = false, PickRed = false, PickGold = false, PickBlue = false, Last = nil}
local Tracker = false

function Plugin:__init()
    PrintChat("This is a quick and dirty twisted fate plugin, that only executes the combo so far: Targets > 3 then Red, otherwise Gold, mana < 20% Blue; Q on stunned enemy; Gold card on teleport")
end

function Plugin:OnTick()
    if Cards.PickRed or Cards.PickGold or Cards.PickBlue then
        if myHero:CanUseSpell(_W) == READY and not Tracker then
            CastSpell(_W)
        end
                    if Cards.PickRed and Cards.Red then
                        CastSpellEx(_W)
                        Cards.PickRed = false
                        return
                    end
                    if Cards.PickGold and Cards.Gold then
                        CastSpellEx(_W)
                        Cards.PickGold = false
                        return
                    end
                    if Cards.PickBlue and Cards.Blue then
                        CastSpellEx(_W)
                        Cards.PickBlue = false
                        return
                    end
                    return
                end
    if Keys.AutoCarry then
        if myHero:CanUseSpell(_W) == READY and not Tracker then
            CastSpell(_W)
        end
        if Tracker then
            local Target = Crosshair:GetTarget()

            if ValidTarget(Target, 1450) then 
                local Count = self:CountEnemies(Target, 100)

                if myHero.mana/myHero.maxMana < 0.2 and Cards.Blue then
                    CastSpellEx(_W)
                    return
                end
                if Count >= 3 and Cards.Red and myHero.mana/myHero.maxMana > 0.2 then
                    CastSpellEx(_W)
                    return
                end
                if Count < 3 and myHero.mana/myHero.maxMana > 0.2 and Cards.Gold then
                    CastSpellEx(_W)
                    return
                end
            end
        end
    end
end

function Plugin:OnCreateObj(obj)
    if GetDistance(obj) < 50 then
        if obj.name:find("Card_Yellow.troy") then
            Cards.Gold = true
        elseif obj.name:find("Card_Red.troy") then
            Cards.Red = true
        elseif obj.name:find("Card_Blue.troy") then
            Cards.Blue = true
        end
    end
end


function Plugin:OnDeleteObj(obj)
    if GetDistance(obj) < 50 then
        if obj.name:find("Card_Yellow.troy") then
            Cards.Gold = false
        elseif obj.name:find("Card_Red.troy") then
            Cards.Red = false
        elseif obj.name:find("Card_Blue.troy") then
            Cards.Blue = false
        end
    end
end

function Plugin:OnProcessSpell(unit, spell)
    if unit.isMe and spell.name == "gate" then
        Cards.PickGold = true
    end
end

function Plugin:CountEnemies(point, range)
        local ChampCount = 0
        for j = 1, heroManager.iCount, 1 do
                local enemyhero = heroManager:getHero(j)
                if myHero.team ~= enemyhero.team and ValidTarget(enemyhero) then
                        if GetDistance(enemyhero, point) <= range then
                                ChampCount = ChampCount + 1
                        end
                end
        end            
        return ChampCount
end

AdvancedCallback:bind('OnGainBuff', function(unit, buff)
    if unit.isMe then
        if buff.name == "pickacard_tracker" then
            Tracker = true
        end
    end
    if unit.team ~= myHero.team and unit.type == "obj_AI_Hero" and myHero:CanUseSpell(_Q) == READY then
         if ValidTarget(unit, 1450) and (buff.type == BUFF_STUN or buff.type == BUFF_ROOT or buff.type == BUFF_KNOCKUP or buff.type == BUFF_SUPPRESS) then
            CastSpell(_Q, unit.x, unit.z)
         end
    end
end)

AdvancedCallback:bind('OnLoseBuff', function(unit, buff)
    if unit.isMe then
        if buff.name == "pickacard_tracker" then
            Tracker = false
        end
    end
end)

Menu = AutoCarry.Plugins:RegisterPlugin(Plugin(), "TFQuicknDirty")